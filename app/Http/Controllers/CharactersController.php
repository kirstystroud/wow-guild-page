<?php

namespace App\Http\Controllers;

use App\Character;
use Illuminate\Http\Request;

class CharactersController extends Controller
{
    /**
     * Handles GET request to /characters
     * Returns view with summary of character data
     */
    public function get(Request $request) {
        return view('characters')->with('characters', Character::all());
    }
}
