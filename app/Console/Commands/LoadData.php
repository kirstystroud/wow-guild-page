<?php

namespace App\Console\Commands;

use CharacterClass;
use Dungeon;
use Meta;
use PetType;
use Race;
use BlizzardApi;
use Illuminate\Console\Command;
use Log;

class LoadData extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:data';

    const SIDE_ALLIANCE = 0;
    const SIDE_HORDE = 1;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check dungeons, races, classes and specs data for changes';

    // Full color map
    protected $_colorMap = [
        'background' => [
            [215,32,112],[171,0,76],[87,0,0],[225,105,26],[180,56,0],[133,11,0],[237,151,22],[205,110,0],[155,61,0],[239,207,20],[207,162,0],[158,113,0],[226,216,20],[183,177,0],[133,128,0],[206,209,24],[159,161,3],[112,115,0],[153,206,27],[108,154,3],[65,108,0],[30,210,96],[4,157,63],[0,110,11],[29,206,169],[4,152,122],[0,107,74],[33,177,214],[3,109,139],[0,81,111],[72,125,193],[38,85,145],[0,39,98],[188,75,195],[145,42,155],[108,8,128],[202,17,191],[173,0,162],[124,0,116],[219,30,160],[149,0,97],[121,0,68],[160,108,44],[108,66,15],[53,16,0],[15,26,31],[117,124,120],[136,145,139],[156,166,159],[211,211,198],[229,107,140]
        ],
        'border' => [
            [97,42,44],[109,69,46],[119,101,36],[118,114,36],[108,118,36],[85,108,48],[76,109,48],[48,108,66],[48,105,107],[48,80,108],[55,60,100],[87,54,100],[100,55,76],[103,51,53],[153,159,149],[38,46,38],[155,94,28]
        ],
        'icon' => [
            [102,0,32],[103,35,0],[103,69,0],[103,86,0],[98,102,0],[80,102,0],[54,102,0],[0,102,30],[0,102,86],[0,72,102],[9,42,94],[86,9,94],[93,10,79],[84,54,10],[177,183,176],[16,20,22],[221,163,90]
        ]
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        Log::debug('Loading data');
        $this->loadClasses();
        $this->loadRaces();
        $this->loadDungeons();
        $this->loadGuildMeta();
        $this->loadPetTypes();
    }

    /**
     * Update list of dungeons
     */
    protected function loadDungeons() {
        Log::debug('Loading dungeons');
        // Make request to Blizzard API to load Dungeons and populate database
        $zones = BlizzardApi::getZones();
        if (!$zones) return false;

        foreach ($zones['zones'] as $zone) {
            // Check if we already know about this
            $dungeon = Dungeon::where('name', $zone['name'])->first();

            if (!$dungeon) {
                Log::info('Found new zone ' . $zone['name']);
                $dungeon = new Dungeon;
                $dungeon->name = $zone['name'];
                $dungeon->min_level = $zone['advisedMinLevel'];
                $dungeon->max_level = $zone['advisedMaxLevel'];
                $dungeon->location = $zone['location']['name'];
                $dungeon->save();
            }

            if (!$dungeon->instance_type) {
                if ($zone['isRaid']) {
                    $dungeon->instance_type = Dungeon::TYPE_RAID;
                } else if($zone['isDungeon']) {
                    $dungeon->instance_type = Dungeon::TYPE_DUNGEON;
                }
                $dungeon->save();
            }

        }
    }

    /**
     * Update list of classes
     */
    protected function loadClasses() {
        Log::debug('Loading classes');
        // Make requests to Blizzard API to load classes
        $classes = BlizzardApi::getClasses();
        if (!$classes) return false;

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
        Log::debug('Loading races');
        $races = BlizzardApi::getRaces();
        if (!$races) return false;

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

    /**
     * Load pet types
     */
    protected function loadPetTypes() {
        Log::debug('Loading pet types');
        $petData = BlizzardApi::getPetTypes();
        if (!$petData) return false;

        // Add new entries as required
        foreach($petData['petTypes'] as $type) {
            $existing = PetType::where('id_ext', $type['id'])->first();

            if (!$existing) {
                $existing = new PetType;
                $existing->id_ext = $type['id'];
                $existing->name = $type['name'];
                $existing->save();
            }
        }

        // Make sure strong/weak against properly set
        $notSet = PetType::where('strong_against', 0)->count();
        if ($notSet) {
            foreach($petData['petTypes'] as $type) {
                // Load this row
                $existing = PetType::where('id_ext', $type['id'])->first();

                // Load rows for strong/weak against
                $strongAgainst = PetType::where('id_ext', $type['strongAgainstId'])->first();
                $existing->strong_against = $strongAgainst->id;

                $weakAgainst = PetType::where('id_ext', $type['weakAgainstId'])->first();
                $existing->weak_against = $weakAgainst->id;

                $existing->save();
            }
        }
    }

    /**
     * Update guild meta information
     */
    protected function loadGuildMeta() {
        Log::debug('Loading guild meta');
        $guildData = BlizzardApi::getGuildProfile();
        if (!$guildData) return false;

        // Store meta information on icon
        $metaValue = [
            Meta::TABARD_ICON => (int) $guildData['emblem']['icon'],
            Meta::TABARD_ICON_COLOR => $guildData['emblem']['iconColor'],
            Meta::TABARD_ICON_COLOR_DATA => $this->_colorMap['icon'][$guildData['emblem']['iconColorId']],
            Meta::TABARD_BORDER => (int) $guildData['emblem']['border'],
            Meta::TABARD_BORDER_COLOR => $guildData['emblem']['borderColor'],
            Meta::TABARD_BORDER_COLOR_DATA => $this->_colorMap['border'][$guildData['emblem']['borderColorId']],
            Meta::TABARD_BACKGROUND_COLOR => $guildData['emblem']['backgroundColor'],
            Meta::TABARD_BACKGROUND_COLOR_DATA => $this->_colorMap['background'][$guildData['emblem']['backgroundColorId']]
        ];

        // Add JSON meta
        Meta::addMeta(Meta::KEY_TABARD, json_encode($metaValue));

        // Download image files into public directory

        // Ring
        switch ($guildData['side']) {
            case self::SIDE_ALLIANCE :
                $ringPath = 'ring-alliance.png';
                break;
            case self::SIDE_HORDE :
                $ringPath = 'ring-horde.png';
                break;
            default : 
                throw new Exception('Unknown guild side');
        }
        $this->downloadToFile($ringPath, 'ring.png');
        // Shadow
        $this->downloadToFile('shadow_00.png', 'shadow.png');
        // Background
        $this->downloadToFile('bg_00.png', 'background.png');
        // Overlay
        $this->downloadToFile('overlay_00.png', 'overlay.png');
        // Border
        $borderPath = 'border_' . $this->zeroFill($guildData['emblem']['border']) . '.png';
        $this->downloadToFile($borderPath, 'border.png');
        // Emblem
        $emblemPath = 'emblem_' . $this->zeroFill($guildData['emblem']['icon']) . '.png';
        $this->downloadToFile($emblemPath, 'emblem.png');
        // Hooks
        $this->downloadToFile('hooks.png', 'hooks.png');

    }

    protected function downloadToFile($remotePath, $localPath) {
        $remoteFullPath = 'http://eu.battle.net/wow/static/images/guild/tabards/' . $remotePath;
        $localFullPath = __DIR__ . '/../../../public/images/' . $localPath;

        $result = file_get_contents($remoteFullPath);
        if (!$result) {
            throw new Exception('Failed to download file from ' . $remoteFullPath);
        }
        file_put_contents($localFullPath, $result);

        if (!file_exists($localFullPath)) {
            throw new Exception('Failed to write to ' . $localFullPath);
        }
    }

    protected function zeroFill($value) {
        if ($value > 9) {
            return $value;
        } else {
            return '0' . $value;
        }
    }
}
