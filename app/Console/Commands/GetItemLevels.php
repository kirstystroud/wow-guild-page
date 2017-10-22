<?php

namespace App\Console\Commands;

use App\Character;
use App\Utilities\BlizzardApi;
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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::debug('Updating item levels');
        $characters = Character::all();
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
            $itemData = BlizzardApi::getCharacterItems($character->name);
            $itemObject = json_decode($itemData, true);

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
