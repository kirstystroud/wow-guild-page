<?php

namespace App\Http\Controllers;

use Character;
use CharacterFilter;
use Illuminate\Http\Request;

class CharactersController extends Controller {

    /**
     * Handles GET request to /characters
     *
     * @return {view}
     */
    public function get() {
        return view('characters.index');
    }

    /**
     * Handles GET request to /characters/data to laod data
     * Returns view with summary of character data
     *
     * @param  {CharacterFilter} $filters filter object constructed from request
     * @return {view}
     */
    public function data(CharacterFilter $filters) {
        // Apply filters from request data
        $characters = Character::filter($filters)->get();
        return view('characters.data')
            ->with('characters', $characters)
            ->with('filters', $filters->filters());
    }
}
