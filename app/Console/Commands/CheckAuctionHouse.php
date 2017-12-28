<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Log;

class CheckAuctionHouse extends Command
{
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
    }

    protected function checkAuctionData($data) {
        // Do we already know about this item in the items table ?
        var_dump($data);
        exit();

        // If pet, do we already know about this pet in the pets table ?

        // Do we have an existing entry for this auction ?
    }
}
