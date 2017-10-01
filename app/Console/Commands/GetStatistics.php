<?php

namespace App\Console\Commands;

use App\Character;
use App\Utilities\BlizzardApi;

use Illuminate\Console\Command;

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
            $char->kills = $kills;
            $char->deaths = $deaths;
            $char->kdr = $kdr;

            $char->save();
        }
    }
}
