<?php

namespace App\Console\Commands;

use App\Character;
use App\Utilities\BlizzardApi;
use Illuminate\Console\Command;

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
        // Make call to Blizzard API to get a list of characters in the guild
        $result = BlizzardApi::getGuildCharacters();

        if (!$result) exit(2);

        $decodedResult = json_decode($result, true);
        $guildMembers = $decodedResult['members'];

        foreach($guildMembers as $member) {
            $this->updateCharacter($member['character']);
        }
    }

    protected function updateCharacter($characterData) {
        // Check for existing
        $char = Character::where('name', $characterData['name'])->first();

        if (!$char) {
            $char = new Character;
            $char->name = $characterData['name'];
            $char->ilvl = 0;        // Handle this in separate command as ilvl requires separate call per character

            // Race and class are fixed so set here
            $char->class = $characterData['class'];
            $char->race = $characterData['race'];
        }

        // Spec and Level change, reset here
        $char->level = $characterData['level'];
        if (isset($characterData['spec'])) {
            $char->spec = $characterData['spec']['name'];    
        } else {
            $char->spec = '-';
        }
        
        $char->save();
    }
}
