<?php

namespace App\Console\Commands;

use Character;
use Profession;
use Recipe;
use CharacterProfession;
use CharacterRecipe;
use BlizzardApi;

use Illuminate\Console\Command;

use Log;

class GetProfessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:professions {--recipes= : Should we also load recipes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update character professions';

    protected $_withRecipes = false;

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
        $this->_withRecipes = isset($this->options()['recipes']) && ($this->options()['recipes'] == 'true');

        Log::debug('Updating character professions');
        $characters = Character::all();
        $progressBar = $this->output->createProgressBar(count($characters));
        // Loop over each character
        foreach ($characters as $char) {

            $data = BlizzardApi::getProfessions($char->name);
            if (!$data) {
                $progressBar->advance();
                continue;
            }

            $this->updateProfessionsForChar($char, $data['professions']['primary']);
            $this->updateProfessionsForChar($char, $data['professions']['secondary']);
            $progressBar->advance();
        }
        $progressBar->finish();
        $this->line('');
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

            // Update icon
            if (!$profession->icon) {
                $profession->icon = $p['icon'];
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
            if ($link->skill != $p['rank']) {
                Log::info($char->name . '\'s skill in ' . $profession->name . ' has increased from ' . ($link->skill ? $link->skill : 0) . ' to ' . $p['rank']);
                $link->skill = $p['rank'];
                $link->save();
            }

            // Do we want to update recipes
            if ($this->_withRecipes) {
                if (!count($p['recipes'])) {
                    return true;
                }
                Log::debug('Checking ' . count($p['recipes']) . ' ' . $profession->name . ' recipes for ' . $char->name);
                // Loop over recipes
                foreach($p['recipes'] as $r) {
                    // Do we have an existing entry for this recipe
                    $existing = Recipe::where('id_ext', $r)->first();
                    if (!$existing) {

                        $recipe = BlizzardApi::getRecipe($r);
                        if (!$recipe) continue;

                        $existing = new Recipe;
                        $existing->id_ext = $r;
                        $existing->name = $recipe['name'];
                        $existing->profession_id = $profession->id;
                        Log::info('Found new ' . $profession->name . ' recipe ' . $existing->name);
                        $existing->save();
                    }

                    $link = CharacterRecipe::where('character_id', $char->id)->where('recipe_id', $existing->id)->first();
                    if (!$link) {
                        Log::info($char->name .  ' has learned the ' . $profession->name . ' recipe ' . $existing->name);
                        $link = new CharacterRecipe;
                        $link->character_id = $char->id;
                        $link->recipe_id = $existing->id;
                        $link->save();
                    }
                }
            }

        }
    }
}
