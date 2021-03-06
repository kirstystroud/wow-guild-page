<?php

namespace App\Console\Commands;

use Character;
use CharacterClass;
use Race;
use Spec;
use BlizzardApi;

use Illuminate\Console\Command;

use Log;

class GetCharacters extends Command {

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        if (!CharacterClass::count()) {
            Log::error('No supporting data found, please run get:data');
            exit(1);
        }

        Log::debug('Updating characters');
        // Make call to Blizzard API to get a list of characters in the guild
        $result = BlizzardApi::getGuildCharacters();

        if (!$result) {
            exit(2);
        }

        $guildMembers = $result['members'];

        foreach ($guildMembers as $member) {
            $this->updateCharacter($member['character']);
        }

        // Get list of characters who are no longer there
        $deletedChars = Character::whereNotIn('id', $this->_existingCharIds)->get();

        // loop over characters
        foreach ($deletedChars as $char) {
            // Professions
            if ($char->professions) {
                foreach ($char->professions as $professions) {
                    $profession->delete();
                }
            }
            // Reputation
            if ($char->reputation) {
                foreach ($char->reputation as $link) {
                    $link->delete();
                }
            }
            // Achievements
            if ($char->achievements) {
                foreach ($char->achievements as $link) {
                    $link->delete();
                }
            }
            // Quests
            if ($char->quests) {
                foreach ($char->quests as $link) {
                    $link->delete();
                }
            }
            // Dungeons
            if ($char->dungeons) {
                foreach ($char->dungeons as $link) {
                    $link->delete();
                }
            }
            Log::info('Deleting character ' . $char->name);
            $char->delete();
        }
    }

    /**
     * Update a single character based on data
     *
     * @param  {array} $characterData
     * @return {void}
     */
    protected function updateCharacter($characterData) {
        // Check for existing
        $char = Character::where('name', $characterData['name'])->first();

        if (!$char) {
            Log::info('Creating new entry for ' . $characterData['name']);
            $char = new Character;
            $char->name = $characterData['name'];
            // Handle this in separate command as ilvl requires separate call per character
            $char->ilvl = 0;

            // Load class
            $class = CharacterClass::where('id_ext', $characterData['class'])->first();
            $char->class_id = $class->id;

            // Load race
            $race = Race::where('id_ext', $characterData['race'])->first();
            $char->race_id = $race->id;
            $char->updateLastActivity();
        }

        // Spec and Level change, reset here
        if ($characterData['level'] != $char->level) {
            Log::info($char->name . '\'s level has increased from ' . ($char->level ? $char->level : 0) . ' to ' . $characterData['level']);
            $char->updateLastActivity();
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

        // Check realm
        if ($characterData['realm'] != $char->server) {
            $char->server = $characterData['realm'];
        }

        $char->save();

        $this->_existingCharIds[] = $char->id;
    }
}
