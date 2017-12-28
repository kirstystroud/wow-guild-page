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
        // TODO load data from remote, currently loading from file
        $filePath = __DIR__ . '/../../../auctions.json';
        $auctionData = json_decode(file_get_contents($filePath), true);

        Log::debug('Checking ' . count($auctionData['auctions']) . ' auctions for pet data');

        // Filter for pet related data only
        $petAuctions = array_filter($auctionData['auctions'], function($k) {
            return isset($k['petSpeciesId']);
        });

        Log::debug('Found ' . count($petAuctions) . ' pet related auctions to check');

        // Loop over pet auctions
        foreach($petAuctions as $p) {
            $this->checkAuctionData($p);
        }

        // Look for auctions which have not been updated in the last hour
        $dateLimit = Date('Y-m-d H:i:s', time() - 3600);
        // Set status to sold where status = selling
        Auction::where('updated_at', '<', $dateLimit)->where('status', Auction::STATUS_SELLING)->update(['status' => Auction::STATUS_SOLD]);
        // Set status to ended where status = active
        Auction::where('updated_at', '<', $dateLimit)->where('status', Auction::STATUS_ACTIVE)->update(['status' => Auction::STATUS_ENDED]);
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

            $auction->save();

            Log::debug('Found new auction for ' . $pet->name);
        } else {
            // Has someone since placed a bid on this item
            if ($auction->bid != $data['bid']) {
                $auction->bid = $data['bid'];
                $auction->status = Auction::STATUS_SELLING;
                $auction->save();

                if (isset($data['petSpeciesId'])) {
                    Log::debug('Auction for ' . $pet->name . ' has bids');
                } else {
                    Log::debug('Auction for ' . $item->name . ' has bids');
                }
            }
        }

    }
}
