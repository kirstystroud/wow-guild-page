<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

use Character;
use Achievement;
use CharacterAchievement;
use BlizzardApi;

class GetAchievements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:achievements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load character achievements';

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

        $progressBar = $this->output->createProgressBar(count($characters));

        foreach($characters as $char) {
            // Pull down list of achievements
            $data = BlizzardApi::getAchievements($char);

            if (!$data) {
                $progressBar->advance();
                continue;
            }

            // Exclude known
            $existingLinks = $char->getEarnedAchievements();
            $toCheck = array_diff($data['achievements']['achievementsCompleted'], $existingLinks);
            if (!count($toCheck)) {
                $progressBar->advance();
                continue;
            }

            Log::debug('Checking ' . count($toCheck) . ' achievements for ' . $char->name);

            // Loop over achievements
            foreach($toCheck as $a) {
                // Do we already know about this
                $achievement = Achievement::where('id_ext', $a)->first();
                if (!$achievement) {
                    $rawAchievement = BlizzardApi::getAchievement($a);
                    if (!$rawAchievement) continue; // failure from API
                    
                    $achievement = new Achievement;
                    $achievement->id_ext = $a;
                    $achievement->title = $rawAchievement['title'];
                    $achievement->description = $rawAchievement['description'];
                    $achievement->points = $rawAchievement['points'];
                    $achievement->save();

                    Log::debug('Found new achievement ' . $rawAchievement['title'] . ' - ' . $rawAchievement['description']);
                }

                // Add link
                $link = CharacterAchievement::where('character_id', $char->id)->where('achievement_id', $achievement->id)->first();
                if (!$link) {
                    $link = new CharacterAchievement;
                    $link->character_id = $char->id;
                    $link->achievement_id = $achievement->id;
                    $link->save();

                    Log::debug($char->name . ' has now earned the achievement ' . $achievement->title);
                }

            }
            $progressBar->advance();

        }

        $progressBar->finish();
        $this->line('');
    }
}
