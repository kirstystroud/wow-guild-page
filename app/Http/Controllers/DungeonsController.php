<?php

namespace App\Http\Controllers;

use Character;
use Dungeon;
use Illuminate\Http\Request;

class DungeonsController extends Controller {

    /**
     * Handles GET requests to /dungeons
     * Returns page framework
     *
     * @return {view}
     */
    public function get() {
        $characters = Character::orderBy('name', 'asc')->get();
        return view('dungeons.index')->with('characters', $characters);
    }

    /**
     * Handles GET requests to /dungeons/data
     * Returns view with empty panels
     *
     * @return {view}
     */
    public function data() {
        $dungeonsData = Dungeon::where('instance_type', Dungeon::TYPE_DUNGEON)->orderBy('min_level', 'asc')->orderBy('max_level', 'asc')->get();
        return view('dungeons.data')->with('dungeons', $dungeonsData);
    }

    /**
     * Handles GET requetss to /dungeons/data/$dungeon
     * Returns panel contents for that dungeon row
     *
     * @param  {Dungeon} $dungeon dungeon object from request
     * @return {array} contains accordion class and rendered view
     */
    public function dungeonData(Dungeon $dungeon) {
        $dungeonClass = $dungeon->getPanelClass();
        return [
            'class' => $dungeonClass,
            'view' => view('dungeons.partials.row')->with('dungeon', $dungeon)->render()
        ];
    }
}
