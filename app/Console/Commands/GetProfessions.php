<?php

namespace App\Console\Commands;

use App\Character;
use App\Profession;
use App\CharacterProfession;
use App\Utilities\BlizzardApi;

use Illuminate\Console\Command;

class GetProfessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:professions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update character professions';

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
        // Loop over each character
        foreach (Character::all() as $char) {

            $data = json_decode(BlizzardApi::getProfessions($char->name), true);

            $this->updateProfessionsForChar($char, $data['professions']['primary']);
            $this->updateProfessionsForChar($char, $data['professions']['secondary']);
        }
    }

    protected function updateProfessionsForChar($char, $professions) {
        // Some lower level characters will not have any professions yet
        if (!$professions) {
            return;
        }
        foreach ($professions as $p) {
            // Only want to create if character has a skil > 0 in this
            if (!$p['rank']) {
                continue;
            }

            // Do we have this profession in the database already
            $profession = Profession::where('id_ext', $p['id'])->first();
            if (!$profession) {
                $profession = new Profession;
                $profession->id_ext = $p['id'];
                $profession->name = $p['name'];
                $profession->save();
            }

            // Does this character have existing entries for this profession
            $link = CharacterProfession::where('character_id', $char->id)->where('profession_id', $profession->id)->first();
            if (!$link) {
                $link = new CharacterProfession;
                $link->character_id = $char->id;
                $link->profession_id = $profession->id;
                $link->save();
            }

            // Always update skill
            $link->skill = $p['rank'];
            $link->save();
        }
    }
}
