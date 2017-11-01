<?php

namespace App\Console\Commands;

use Character;
use BlizzardApi;

use Log;
use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class GetStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get statistics for a character';

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
        $progressBar = $this->output->createProgressBar(count($characters));

        foreach($characters as $char) {
            $data = json_decode(BlizzardApi::getStats($char->name), true);
            $deaths = $data['statistics']['subCategories'][3]['statistics'][0]['quantity'];
            $kills = $data['statistics']['subCategories'][2]['statistics'][0]['quantity'];
            if (!$kills) {
                $kills = 0;
            }
            if (!$deaths) {
                $deaths = 0;
            }

            if ($deaths) {
                $kdr = round($kills / $deaths);
            } else {
                $kdr = $kills;
            }

            $deathsPerLevel = round($deaths / $char->level, 2);
            if ($char->kills != $kills) {
                Log::info($char->name . '\'s kills has increased from ' . ($char->kills ? $char->kills : 0) . ' to ' . $kills);
                $char->kills = $kills;
            }

            if ($char->deaths != $deaths) {
                Log::info($char->name . '\'s deaths has increased from ' . ($char->deaths ? $char->deaths : 0) . ' to ' . $deaths);
                $char->deaths = $deaths;
            }

            if ($char->kdr != $kdr) {
                // Log::info($char->name . '\'s KDR has changed from ' . $char->kdr . ' to ' . $kdr);
                $char->kdr = $kdr;
            }

            $char->save();
            $progressBar->advance();
        }
        $progressBar->finish();
        $this->line('');
    }
}
