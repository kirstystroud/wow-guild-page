<?php

namespace App\Http\Controllers;

use Character;

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
}
