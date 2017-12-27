<?php

namespace App\Console\Commands;

use Character;
use BlizzardApi;
use Illuminate\Console\Command;

use Log;

class GetItemLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:ilvls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make requests to pull down character item levels';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $characters = Character::all();

        if (!$characters) {
            Log::error('No characters found, please run get:characters');
            exit(1);
        }

        Log::debug('Updating item levels');

        $progressBar = $this->output->createProgressBar(count($characters));

        foreach($characters as $character) {
            $this->getItemLevel($character);
            $progressBar->advance();
        }
        $progressBar->finish();
        $this->line('');
    }

    protected function getItemLevel($character) {
        // Low-level characters will not have this available
        try {
            $itemObject = BlizzardApi::getCharacterItems($character);

            if (!$itemObject) return false;

            if ($itemObject['items']['averageItemLevelEquipped']) {
                if ($character->ilvl != $itemObject['items']['averageItemLevelEquipped']) {
                    Log::info($character->name . '\'s item level has changed from ' . ($character->ilvl ? $character->ilvl : 0) . ' to ' . $itemObject['items']['averageItemLevelEquipped']);
                    $character->ilvl = $itemObject['items']['averageItemLevelEquipped'];
                    $character->save();
                }
            }
        } catch (Exception $e) {

        }
    }
}
