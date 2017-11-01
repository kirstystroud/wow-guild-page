<?php

namespace App\Http\Controllers;

use Character;
use Dungeon;

use Illuminate\Http\Request;

class RaidsController extends Controller
{
    /**
     * Handles GET requests to /raids
     * Returns page framework
     */
    public function get(Request $request) {
        $characters = Character::orderBy('name', 'asc')->get();
        return view('raids')->with('characters', $characters);
    }

    /**
     * Handles GET requests to /raids/data
     * Returns view with empty panels
     */
    public function data() {
        $raidsData = Dungeon::where('instance_type', Dungeon::TYPE_RAID)->orderBy('min_level', 'asc')->orderBy('max_level', 'asc')->get();
        return view('partials.raids')->with('raids', $raidsData);
    }

    public function raidData(Dungeon $dungeon) {
        $dungeonClass = $dungeon->getPanelClass();
        return [
            'class' => 'panel-info profession-panel',
            'view' => view('partials.raid-row')->with('raid', $dungeon)->render()
        ];
    }
}
