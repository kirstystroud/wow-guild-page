<?php

namespace App\Console\Commands;

use App\Dungeon;
use App\Utilities\BlizzardApi;
use Illuminate\Console\Command;

class LoadDungeons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:dungeons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update list of WoW dungeons and raids';

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
        // Make request to Blizzard API to load Dungeons and populate database
        $zones = json_decode(BlizzardApi::getZones(), true);

        foreach ($zones['zones'] as $zone) {
            // Check if we already know about this
            $existing = Dungeon::where('name', $zone['name'])->count();

            if (!$existing) {
                $dungeon = new Dungeon;
                $dungeon->name = $zone['name'];
                $dungeon->min_level = $zone['advisedMinLevel'];
                $dungeon->max_level = $zone['advisedMaxLevel'];
                $dungeon->location = $zone['location']['name'];
                $dungeon->save();
            }
        }
    }
}
