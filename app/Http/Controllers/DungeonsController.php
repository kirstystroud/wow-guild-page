<?php

namespace App\Http\Controllers;

use App\Character;
use App\Dungeon;
use Illuminate\Http\Request;

class DungeonsController extends Controller
{
    /**
     * Get list of dungeons with available characters
     */
    public function get(Request $request) {
        $characters = Character::orderBy('name', 'asc')->get();
        $dungeonsData = Dungeon::orderBy('min_level', 'asc')->orderBy('max_level', 'asc')->get();
        return view('dungeons')->with('dungeons', $dungeonsData)->with('characters', $characters);
    }
}
