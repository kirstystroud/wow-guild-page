<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Auction;
use Item;
use Pet;
use PetType;

use BlizzardApi;
use Log;

class CheckAuctionHouse extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:auctions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for current AH data, currently supporting pets only';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        echo 'Loading file' . PHP_EOL;
        $filePath = __DIR__ . '/../../../auctions.json';

        // Pull down file from remote and put into auctions.json so have local copy
        $auctionDataRemote = BlizzardApi::getAuctionDataUrl();
        $auctionData = file_get_contents($auctionDataRemote);
        file_put_contents($filePath, $auctionData);

        echo 'Downloaded auction data' . PHP_EOL;

        // TODO load data from remote, currently loading from file
        $auctionData = json_decode($auctionData, true);

        Log::debug('Checking ' . count($auctionData['auctions']) . ' auctions for pet data');

        // Filter for pet related data only
        $petAuctions = array_filter($auctionData['auctions'], function($k) {
            return isset($k['petSpeciesId']);
        });

        Log::debug('Found ' . count($petAuctions) . ' pet related auctions to check');

        $progressBar = $this->output->createProgressBar(count($petAuctions));

        // Loop over pet auctions
        $existingAuctions = [];
        foreach($petAuctions as $p) {
            $this->checkAuctionData($p);
            $existingAuctions[] = $p['auc'];
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->line('');

        echo 'Cleaning up old auctions' . PHP_EOL;

        // Look for auctions with status active / expired where id not in $existingAuctions
        $expiredAuctions = Auction::whereNotIn('id_ext', $existingAuctions)->whereIn('status', [Auction::STATUS_ACTIVE, Auction::STATUS_SELLING])->get();
        echo 'Found ' . count($expiredAuctions) . ' expired auctions to check' . PHP_EOL;

        foreach($expiredAuctions as $auction) {
            $this->expireAuction($auction);
        }
    }

    protected function checkAuctionData($data) {

        // Do we already know about this item in the items table ?
        $item = Item::where('id_ext', $data['item'])->first();
        if (!$item) {
            $itemData = BlizzardApi::getItem($data['item']);

            $item = new Item;
            $item->id_ext = $data['item'];
            $item->name = $itemData['name'];
            $item->description = $itemData['description'];
            $item->save();

            Log::debug('Found new item ' . $item->name);
        }

        // If pet, do we already know about this pet in the pets table ?
        if (isset($data['petSpeciesId'])) {
            $pet = Pet::where('id_ext', $data['petSpeciesId'])->first();
            if (!$pet) {
                $petData = BlizzardApi::getPetSpecies($data['petSpeciesId']);

                $pet = new Pet;
                $pet->id_ext = $data['petSpeciesId'];
                $pet->name = $petData['name'];

                if (strlen($petData['description']) <= 250) {
                    $pet->description = $petData['description'];
                } else {
                    $pet->description = substr($petData['description'], 0, 250) . ' ...';
                }

                if (strlen($petData['source']) <= 250) {
                    $pet->source = $petData['source'];
                } else {
                    $pet->source = substr($petData['source'], 0, 250) . ' ...';
                }

                $petType = PetType::where('id_ext', $petData['petTypeId'])->first();
                $pet->type = $petType->id;
                $pet->save();

                Log::debug('Found new pet ' . $pet->name);
            }
        }

        // Do we have an existing entry for this auction ?
        $auction = Auction::where('id_ext', $data['auc'])->whereIn('status', [Auction::STATUS_SELLING, Auction::STATUS_ACTIVE])->first();

        if (!$auction) {
            // New auction
            $auction = new Auction;
            $auction->id_ext = $data['auc'];
            $auction->item_id = $item['id'];
            $auction->bid = $data['bid'];
            $auction->buyout = $data['buyout'];
            $auction->status = Auction::STATUS_ACTIVE;
            $auction->time_left = $this->timeLeftToInteger($data['timeLeft']);

            if (isset($data['petSpeciesId'])) {
                $auction->pet_id = $pet->id;
                $auction->pet_level = $data['petLevel'];
                $auction->pet_quality = $data['petQualityId'];
            }

            $auction->save();

            Log::debug('Found new auction for ' . $pet->name);
        } else {
            // Has someone since placed a bid on this item
            if ($auction->bid != $data['bid']) {
                $auction->bid = $data['bid'];
                $auction->status = Auction::STATUS_SELLING;

                if (isset($data['petSpeciesId'])) {
                    Log::debug('Auction for ' . $pet->name . ' has bids');
                } else {
                    Log::debug('Auction for ' . $item->name . ' has bids');
                }
            }

            $auction->time_left = $this->timeLeftToInteger($data['timeLeft']);
            $auction->save();
        }

    }

    /**
     * Auction has expired, update database according to status and time left
     * @param {Auction} $auction
     */
    protected function expireAuction($auction) {
        if ($auction->pet_id) {
            $auctionName = $auction->pet->name;
        } else {
            $auctionName = $auction->item->name;
        }

        // Track possible states
        $timedOut = false;
        $wentToBuyout = false;
        $sinceLastUpdated = strtotime(time()) - strtotime($auction->updated_at);
        // echo $sinceLastUpdated . PHP_EOL;
        // exit();

        switch($auction->time_left) {
            case Auction::TIME_LEFT_SHORT :
                // Short time left, have to assume expired
                $timedOut = true;
                break;
            case Auction::TIME_LEFT_MEDIUM :
                // 30min - 2hr to go
                if ($sinceLastUpdated > 1800) {
                    // not heard in last half hour, have to assume timed out
                    $timedOut = true;
                } else {
                    // Updated less than 30min ago, must have been bought out
                    $wentToBuyout = true;
                }
                break;
            case Auction::TIME_LEFT_LONG :
                // 2-12 hr to go
                if ($sinceLastUpdated > 7200) {
                    // Not heard in last two hours, have to assume timed out
                    $timedOut = true;
                } else {
                    $wentToBuyout = true;
                }
                break;
            case Auction::TIME_LEFT_VERY_LONG :
                // Over 12 hr to go
                if ($sinceLastUpdated > 43200) {
                    // Not heard in last 12 hours, have to assume timed out
                    $timedOut = true;
                } else {
                    $wentToBuyout = true;
                }
                break;
            default :
                throw new Exception('Unknown time left ' . $auction->time_left);
        }

        if ($wentToBuyout) {
            // Update with buyout price
            $auction->status = Auction::STATUS_SOLD;
            // Account for auctions with no buyout price
            $auction->sell_price = $auction->buyout ? $auction->buyout : $auction->bid;
            Log::debug('Auction for ' . $auctionName . ' has been bought out for ' . $auction->buyoutToGold());
        } elseif ($timedOut) {
            if ($auction->status == Auction::STATUS_SELLING) {
                // Assume auction went for latest bid
                $auction->status = Auction::STATUS_SOLD;
                $auction->sell_price = $auction->bid;
                Log::debug('Auction for ' . $auctionName . ' has sold for ' . $auction->bidToGold());
            } else {
                // Assume auction expired
                $auction->status = Auction::STATUS_ENDED;
                Log::debug('Auction for ' . $auctionName . ' has expired');
            }
        } else {
            // Should not get in here
        }

        $auction->save();
    }

    /**
     * Convert time left string into integer mapping for database
     * @param {string} $timeLeft
     * @return {int}
     */
    protected function timeLeftToInteger($timeLeft) {
        $rtn = false;
        switch ($timeLeft) {
            case 'VERY_LONG' :
                $rtn = Auction::TIME_LEFT_VERY_LONG;
                break;
            case 'LONG' :
                $rtn = Auction::TIME_LEFT_LONG;
                break;
            case 'MEDIUM' :
                $rtn = Auction::TIME_LEFT_MEDIUM;
                break;
            case 'SHORT' :
                $rtn = Auction::TIME_LEFT_SHORT;
                break;
            default :
                throw new Exception('Unknown time left ' . $timeLeft);
        }

        return $rtn;
    }
}
