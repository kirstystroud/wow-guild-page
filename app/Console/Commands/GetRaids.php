<?php

namespace App\Console\Commands;

use Character;
use CharacterDungeon;
use Dungeon;
use BlizzardApi;

use Log;

use Illuminate\Console\Command;

class GetRaids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:raids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for updates on how many times characters have run each raid';

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

        Log::debug('Updated raids');

        $progressBar = $this->output->createProgressBar(count($characters));

        foreach($characters as $char) {

            $data = BlizzardApi::getRaids($char);

            if (!$data) {
                $progressBar->advance();
                continue;
            }

            foreach ($data['progression']['raids'] as $r) {

                $dungeon = Dungeon::where('name', $r['name'])->first();
                if (!$dungeon) continue;

                // Add new character dungeon link if required
                $link = CharacterDungeon::where('dungeon_id', $dungeon->id)->where('character_id', $char->id)->first();
                if (!$link) {
                    $link = new CharacterDungeon;
                    $link->character_id = $char->id;
                    $link->dungeon_id = $dungeon->id;
                    $link->save();
                }

                // Update lfr if changed
                if ($link->lfr != $r['lfr']) {
                    $link->lfr = $r['lfr'];
                    Log::info($char->name . ' has now run ' . $dungeon->name . ' ' . $r['lfr'] . ' times via raid finder');
                }

                // Update normal if changed
                if ($link->normal != $r['normal']) {
                    $link->normal = $r['normal'];
                    Log::info($char->name . ' has now run ' . $dungeon->name . ' ' . $r['normal'] . ' times on normal difficulty');
                }

                // Update heroic if changed
                if ($link->heroic != $r['heroic']) {
                    $link->heroic = $r['heroic'];
                    Log::info($char->name . ' has now run ' . $dungeon->name . ' ' . $r['heroic'] . ' times on heroic difficulty');
                }

                // Update mythic if changed
                if ($link->mythic != $r['mythic']) {
                    $link->mythic = $r['mythic'];
                    Log::info($char->name . ' has now run ' . $dungeon->name . ' ' . $r['mythic'] . ' times on mythic difficulty');
                }

                $link->save();
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->line('');
    }
}
