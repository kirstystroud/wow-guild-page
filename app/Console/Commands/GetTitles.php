<?php

namespace App\Console\Commands;

use Character;
use Title;
use BlizzardApi;

use Log;
use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class GetTitles extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:titles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load titles for guild characters';

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
        $characters = Character::all();

        if (!$characters) {
            Log::error('No characters found, please run get:characters');
            exit(1);
        }

        Log::debug('Updating character titles');

        $progressBar = $this->output->createProgressBar(count($characters));

        foreach ($characters as $char) {
            $data = BlizzardApi::getTitles($char);

            if (!$data) {
                $progressBar->advance();
                continue;
            }

            // loop over all titles
            foreach ($data['titles'] as $t) {
                $title = Title::where('id_ext', $t['id'])->first();
                if (!$title) {
                    $title = new Title;
                    $title->id_ext = $t['id'];
                    $title->name = $t['name'];
                    $title->save();
                }

                if (isset($t['selected']) && $t['selected'] && ($title->id != $char->title_id)) {
                    $char->title_id = $title->id;
                    $char->updateLastActivity();
                    $char->save();
                    Log::info($char->name . '\'s title is now ' . trim(str_replace('%s', '', $title->name)));
                }
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->line('');
    }
}
