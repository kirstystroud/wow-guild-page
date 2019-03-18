<?php

namespace App\Http\Controllers;

use Character;
use Dungeon;

use Illuminate\Http\Request;

class RaidsController extends Controller {

    /**
     * Handles GET requests to /raids
     * Returns page framework
     *
     * @return {view}
     */
    public function get() {
        $characters = Character::orderBy('name', 'asc')->get();
        return view('raids.index')->with('characters', $characters);
    }

    /**
     * Handles GET requests to /raids/data
     * Returns view with empty panels
     *
     * @return {view}
     */
    public function data() {
        $raidsData = Dungeon::where('instance_type', Dungeon::TYPE_RAID)->orderBy('min_level', 'asc')->orderBy('max_level', 'asc')->get();
        return view('raids.data')->with('raids', $raidsData);
    }

    /**
     * Handles GET requests to /raids/data/{$dungeon}
     * Returns view with summary of runs by character for this raid
     *
     * @param  {Dungeon} $dungeon dungeon object from request
     * @return {array} contains panel class and rendered view
     */
    public function raidData(Dungeon $dungeon) {
        $dungeonClass = count($dungeon->getCharacterRaidData()) ? 'panel-info profession-panel' : 'panel-danger';
        return [
            'class' => $dungeonClass,
            'view' => view('raids.partials.row')->with('raid', $dungeon)->render()
        ];
    }
}
