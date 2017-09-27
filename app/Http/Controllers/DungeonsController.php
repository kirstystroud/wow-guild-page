<?php

namespace App\Http\Controllers;

use App\Character;
use App\Dungeon;
use Illuminate\Http\Request;

class DungeonsController extends Controller
{
    /**
     * Handles GET requests to /dungeons
     * Returns page framework
     */
    public function get(Request $request) {
        $characters = Character::orderBy('name', 'asc')->get();
        return view('dungeons')->with('characters', $characters);
    }

    /**
     * Handles GET requests to /dungeons/data
     * Returns view with empty panels
     */
    public function data() {
        $dungeonsData = Dungeon::orderBy('min_level', 'asc')->orderBy('max_level', 'asc')->get();
        return view('partials.dungeons')->with('dungeons', $dungeonsData);
    }
}
