<?php

namespace App\Console\Commands;

use Log;
use Illuminate\Console\Command;

use Character;
use CharacterQuest;
use Quest;
use Category;
use BlizzardApi;

class GetQuests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:quests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get quest progress';

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
        $characters = Character::all();

        if (!$characters) {
            Log::error('No characters found, please run get:characters');
            exit(1);
        }

        Log::debug('Updating character quests');

        $progressBar = $this->output->createProgressBar(count($characters));

        foreach($characters as $char) {
            $data = BlizzardApi::getQuests($char);
            if (!$data) {
                $progressBar->advance();
                continue;
            }

            $existingLinks = $char->getCompletedQuestIdExts();
            $toCheck = array_diff($data['quests'], $existingLinks);
            if (!count($toCheck)) {
                $progressBar->advance();
                continue;
            }

            Log::info('Checking ' . count($toCheck) . ' quests for ' . $char->name);
            foreach ($toCheck as $q) {
                // Do we already know about this quest
                $quest = Quest::where('id_ext', $q)->first();
                if (!$quest) {
                    $rawQuest = BlizzardApi::getQuest($q);

                    if (!isset($rawQuest['category'])) {
                        $rawQuest['category'] = '-';
                    }

                    // Do we know about this category
                    $category = Category::where('name', $rawQuest['category'])->first();
                    if (!$category) {
                        Log::debug('Found new category ' . $rawQuest['category']);
                        $category = new Category;
                        $category->name = $rawQuest['category'];
                        $category->save();
                    }

                    Log::debug('Found new quest ' . $rawQuest['title'] . ' in ' . $category->name);

                    $quest = new Quest;
                    $quest->id_ext = $q;
                    $quest->name = $rawQuest['title'];
                    $quest->category_id = $category->id;
                    $quest->req_level = $rawQuest['reqLevel'];
                    $quest->save();
                }

                // Is this character already linked to this quest
                $link = CharacterQuest::where('character_id', $char->id)->where('quest_id', $quest->id)->first();
                if (!$link) {
                    Log::debug($char->name . ' has now completed the quest ' . $quest->name);
                    $link = new CharacterQuest;
                    $link->character_id = $char->id;
                    $link->quest_id = $quest->id;
                    $link->save();
                }
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->line('');
    }
}
