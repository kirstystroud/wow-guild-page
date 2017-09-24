<?php

namespace App\Http\Controllers;

use App\Character;
use Illuminate\Http\Request;

class CharactersController extends Controller
{

    /**
     * Handles GET request to /characters
     */
    public function get(Request $request) {
        return view('characters');
    }

    /**
     * Handles GET request to /characters/data to laod data
     * Returns view with summary of character data
     */
    public function data(Request $request) {

        $sorting = $request->sort;

        if (!$sorting) {
            // By default order by level, then alphabetically by name
            $characters = Character::orderBy('level', 'asc')->orderBy('name', 'asc')->get();
            $sortOutcome = ['level' => 'asc'];
        } else {
            // Check which keys are set
            $sortKeys = array_keys($sorting);

            // Assuming only one key set here
            switch($sortKeys[0]) {
                case 'class' :
                    $characterQuery = Character::join('classes', 'classes.id', 'characters.class_id')->orderBy('classes.name', $sorting[$sortKeys[0]]);
                    break;
                case 'race' :
                    $characterQuery = Character::join('races', 'races.id', 'characters.race_id')->orderBy('races.name', $sorting[$sortKeys[0]]);
                    break;
                case 'spec' :
                    $characterQuery = Character::select(['characters.*', 'specs.name AS spec_name'])->leftJoin('specs', 'specs.id', 'characters.spec_id')->orderBy('specs.name', $sorting[$sortKeys[0]]);
                    break;
                default :
                    $characterQuery = Character::orderBy($sortKeys[0], $sorting[$sortKeys[0]]);
            }

            // Add sorting by name if not already been asked for
            if ($sortKeys[0] !== 'name') {
                $characterQuery = $characterQuery->orderBy('characters.name', 'asc');
            }

            $characters = $characterQuery->get();
            $sortOutcome = [$sortKeys[0] => $sorting[$sortKeys[0]]];
        }

        return view('partials.characters')->with('characters', $characters)->with('sorting', $sortOutcome);
    }
}
