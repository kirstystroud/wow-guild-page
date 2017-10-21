<?php

namespace App\Console\Commands;

use App\Character;
use App\Faction;
use App\Reputation;
use App\Utilities\BlizzardApi;

use Log;
use Illuminate\Console\Command;

class GetReputation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:reputation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load character reputation standings';

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

            $data = json_decode(BlizzardApi::getReputation($char->name), true);

            if (!$data) {
                continue;
            }

            foreach($data['reputation'] as $r) {
                // Add new faction row if we don't already know about this faction
                $faction = Faction::where('id_ext', $r['id'])->first();

                if (!$faction) {
                    Log::info('Registering new faction ' . $r['name']);
                    $faction = new Faction;
                    $faction->id_ext = $r['id'];
                    $faction->name = $r['name'];
                    $faction->save();
                }

                // Update character standing with that faction
                $reputation = $char->reputation->where('faction_id', $faction->id)->first();
                if (!$reputation) {
                    $reputation = new Reputation;
                    $reputation->character_id = $char->id;
                    $reputation->faction_id = $faction->id;
                }

                if ($reputation->standing != $r['standing']) {
                    Log::info($char->name . ' is now ' . Reputation::getStandings()[$r['standing']] . ' with ' . $faction->name);
                    $reputation->standing = $r['standing'];    
                }
                
                $reputation->current = $r['value'];
                $reputation->max = $r['max'];
                $reputation->save();
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->line('');
    }
}