<?php

namespace App\Console\Commands;

use App\CharacterClass;
use App\Dungeon;
use App\Race;
use App\Utilities\BlizzardApi;
use Illuminate\Console\Command;
use Log;

class LoadData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check dungeons, races, classes and specs data for changes';

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
        Log::info('Loading data');
        $this->loadClasses();
        $this->loadRaces();
        $this->loadDungeons();
    }

    /**
     * Update list of dungeons
     */
    protected function loadDungeons() {
        Log::info('Loading dungeons');
        // Make request to Blizzard API to load Dungeons and populate database
        $zones = json_decode(BlizzardApi::getZones(), true);

        foreach ($zones['zones'] as $zone) {
            // Check if we already know about this
            $existing = Dungeon::where('name', $zone['name'])->count();

            if (!$existing) {
                Log::info('Found new zone ' . $zone['name']);
                $dungeon = new Dungeon;
                $dungeon->name = $zone['name'];
                $dungeon->min_level = $zone['advisedMinLevel'];
                $dungeon->max_level = $zone['advisedMaxLevel'];
                $dungeon->location = $zone['location']['name'];
                $dungeon->save();
            }
        }
    }

    /**
     * Update list of classes
     */
    protected function loadClasses() {
        Log::info('Loading classes');
        // Make requests to Blizzard API to load classes
        $classes = json_decode(BlizzardApi::getClasses(), true);

        foreach ($classes['classes'] as $c) {
            $class = CharacterClass::where('id_ext', $c['id'])->first();
            if (!$class) {
                Log::info('Found new class ' . $c['name']);
                $class = new CharacterClass;
                $class->id_ext = $c['id'];
                $class->name = $c['name'];
                $class->save();
            }
        }
    }

    /**
     * Update list of races
     */
    protected function loadRaces() {
        Log::info('Loading races');
        $races = json_decode(BlizzardApi::getRaces(), true);

        foreach ($races['races'] as $r) {
            $race = Race::where('id_ext', $r['id'])->first();
            if (!$race) {
                Log::info('Found new race ' . $r['name']);
                $race = new Race;
                $race->id_ext = $r['id'];
                $race->name = $r['name'];
                $race->save();
            }
        }
    }
}
