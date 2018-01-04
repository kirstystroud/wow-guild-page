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

    private $_dateRun = false;

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
        $this->_dateRun = Date('Y-m-d H:i:s');

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

        if(!$auctionData['auctions']) return false;

        // Filter for pet related data only
        $petAuctions = array_filter($auctionData['auctions'], function($k) {
            return isset($k['petSpeciesId']);
        });

        // Update poll status on all active auctions
        Auction::where('poll_status', Auction::POLL_STATUS_PROCESSED)->update(['poll_status' => Auction::POLL_STATUS_PENDING]);

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

        // Look for auctions with poll_status pending
        $expiredAuctions = Auction::where('poll_status', Auction::POLL_STATUS_PENDING)->get();
        echo 'Found ' . count($expiredAuctions) . ' expired auctions to check' . PHP_EOL;

        if (!count($expiredAuctions)) return true;

        $expiredProgressBar = $this->output->createProgressBar(count($expiredAuctions));
        foreach($expiredAuctions as $auction) {
            $auction->expire();
            $expiredProgressBar->advance();
        }
        $expiredProgressBar->finish();
        $this->line('');
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

            if (isset($data['petSpeciesId'])) {
                $auction->pet_id = $pet->id;
                $auction->pet_level = $data['petLevel'];
                $auction->pet_quality = $data['petQualityId'];
            }

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

        }

        $auction->time_left = $this->timeLeftToInteger($data['timeLeft']);
        $auction->date_last_seen = $this->_dateRun;
        $auction->poll_status = Auction::POLL_STATUS_PROCESSED;
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
