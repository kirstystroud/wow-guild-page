<?php

namespace App\Console\Commands;

use Character;
use BlizzardApi;

use Log;
use Illuminate\Console\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class GetStatistics extends Command {

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

        Log::debug('Updating statistics');

        $progressBar = $this->output->createProgressBar(count($characters));

        foreach ($characters as $char) {
            $data = BlizzardApi::getStats($char);
            if (!$data) {
                $progressBar->advance();
                continue;
            }

            $deaths = $data['statistics']['subCategories'][3]['statistics'][0]['quantity'];
            $kills = $data['statistics']['subCategories'][2]['statistics'][0]['quantity'];
            $pvpKills = $data['totalHonorableKills'];

            if (!$kills) {
                $kills = 0;
            }
            if (!$deaths) {
                $deaths = 0;
            }
            if (!$pvpKills) {
                $pvpKills = 0;
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
                $char->updateLastActivity();
            }

            if ($char->deaths != $deaths) {
                Log::info($char->name . '\'s deaths has increased from ' . ($char->deaths ? $char->deaths : 0) . ' to ' . $deaths);
                $char->deaths = $deaths;
                $char->updateLastActivity();
            }

            if ($char->pvp_kills != $pvpKills) {
                Log::info($char->name . '\'s PVP kills has increased from ' . ($char->pvp_kills ? $char->pvp_kills : 0) . ' to ' . $pvpKills);
                $char->pvp_kills = $pvpKills;
                $char->updateLastActivity();
            }

            if ($char->kdr != $kdr) {
                // Log::info($char->name . '\'s KDR has changed from ' . $char->kdr . ' to ' . $kdr);
                $char->kdr = $kdr;
                $char->updateLastActivity();
            }

            $dungeonsEntered = $data['statistics']['subCategories'][5]['statistics'][0]['quantity'];
            $raidsEntered = $data['statistics']['subCategories'][5]['statistics'][1]['quantity'] + $data['statistics']['subCategories'][5]['statistics'][2]['quantity'];

            if (!$dungeonsEntered) {
                $dungeonsEntered = 0;
            }
            if (!$raidsEntered) {
                $raidsEntered = 0;
            }

            if ($char->dungeons_entered != $dungeonsEntered) {
                Log::info($char->name . ' has now entered ' . $dungeonsEntered . ' dungeon(s)');
                $char->dungeons_entered = $dungeonsEntered;
                $char->updateLastActivity();
            }

            if ($char->raids_entered != $raidsEntered) {
                Log::info($char->name . ' has now entered ' . $raidsEntered . ' raid(s)');
                $char->raids_entered = $raidsEntered;
                $char->updateLastActivity();
            }

            $char->save();
            $progressBar->advance();
        }
        $progressBar->finish();
        $this->line('');
    }
}
