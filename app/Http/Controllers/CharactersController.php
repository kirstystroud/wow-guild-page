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

        $sorting = $request->sort;

        if (!$sorting) {
            // By default order by level, then alphabetically by name
            $characters = Character::orderBy('level', 'asc')->orderBy('name', 'asc')->get();
            $sortOutcome = ['level' => 'asc'];
        } else {
            // Check which keys are set
            $sortKeys = array_keys($sorting);

            // Assuming only one key set here
            $characterQuery = Character::orderBy($sortKeys[0], $sorting[$sortKeys[0]]);

            // Add sorting by name if not already been asked for
            if ($sortKeys[0] !== 'name') {
                $characterQuery = $characterQuery->orderBy('name', 'asc');
            }

            $characters = $characterQuery->get();
            $sortOutcome = [$sortKeys[0] => $sorting[$sortKeys[0]]];
        }

        return view('characters')->with('characters', $characters)->with('sorting', $sortOutcome);
    }
}
