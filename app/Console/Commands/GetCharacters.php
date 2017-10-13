<?php

namespace App\Console\Commands;

use App\Character;
use App\CharacterClass;
use App\Race;
use App\Spec;
use App\Utilities\BlizzardApi;

use Illuminate\Console\Command;

use Log;

class GetCharacters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:characters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll Blizzard API to get latest character information';

    protected $_existingCharIds;

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
        Log::debug('Updating characters');
        // Make call to Blizzard API to get a list of characters in the guild
        $result = BlizzardApi::getGuildCharacters();

        if (!$result) exit(2);

        $decodedResult = json_decode($result, true);
        $guildMembers = $decodedResult['members'];

        foreach($guildMembers as $member) {
            $this->updateCharacter($member['character']);
        }

        // Get list of characters who are no longer there
        $deletedChars = Character::whereNotIn('id', $this->_existingCharIds)->get();

        // loop over characters
        foreach ($deletedChars as $char) {
            if ($char->professions) {
                foreach ($char->professions as $professions) {
                    $profession->delete();
                }
            }
            Log::info('Deleting character ' . $char->name);
            $char->delete();
        }
    }

    protected function updateCharacter($characterData) {
        // Check for existing
        $char = Character::where('name', $characterData['name'])->first();

        if (!$char) {
            Log::info('Creating new entry for ' . $characterData['name']);
            $char = new Character;
            $char->name = $characterData['name'];
            $char->ilvl = 0;        // Handle this in separate command as ilvl requires separate call per character

            // Load class
            $class = CharacterClass::where('id_ext', $characterData['class'])->first();
            $char->class_id = $class->id;

            // Load race
            $race = Race::where('id_ext', $characterData['race'])->first();
            $char->race_id = $race->id;
        }

        // Spec and Level change, reset here
        if ($characterData['level'] != $char->level) {
            Log::info($char->name . '\'s level has increased from ' . $char->level . ' to ' . $characterData['level']);
        }
        $char->level = $characterData['level'];
        if (isset($characterData['spec'])) {

            // Does spec already exist
            $spec = Spec::where('class_id', $char->class_id)->where('name', $characterData['spec']['name'])->first();
            if (!$spec) {
                $spec = new Spec;
                $spec->class_id = $char->class_id;
                $spec->name = $characterData['spec']['name'];
                $spec->icon = $characterData['spec']['icon'];

                switch($characterData['spec']['role']) {
                    case 'HEALING' :
                        $spec->role = Spec::ROLE_HEALER;
                        break;
                    case 'TANK' :
                        $spec->role = Spec::ROLE_TANK;
                        break;
                    case 'DPS' :
                        $spec->role = Spec::ROLE_DPS;
                        break;
                    default :
                        $spec->role = Spec::ROLE_DPS;
                }

                $spec->save();
            }
            if ($char->spec_id != $spec->id) {
                Log::info($char->name . '\'s spec is now ' . $spec->name);
                $char->spec_id = $spec->id;
            }
        }
        
        $char->save();

        $this->_existingCharIds[] = $char->id;
    }
}
