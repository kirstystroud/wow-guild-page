<?php

namespace App\Http\Controllers;

use Character;
use CharacterFilter;
use Illuminate\Http\Request;

class CharactersController extends Controller {

    /**
     * Handles GET request to /characters
     */
    public function get(Request $request) {
        return view('characters.index');
    }

    /**
     * Handles GET request to /characters/data to laod data
     * Returns view with summary of character data
     */
    public function data(CharacterFilter $filters) {
        $characters = Character::filter($filters)->get();
        return view('characters.data')
            ->with('characters', $characters)
            ->with('filters', $filters->filters());
    }
}
