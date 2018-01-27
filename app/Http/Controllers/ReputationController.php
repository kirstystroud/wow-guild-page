<?php

namespace App\Http\Controllers;

use Character;
use Faction;

use Illuminate\Http\Request;

class ReputationController extends Controller
{
    /**
     * Handles GET requests to /reputation
     */
    public function get() {
        $characters = Character::orderBy('name', 'asc')->get();
        return view('reputation.index')->with('characters', $characters);
    }

    /**
     * Handles GET requests to /reputstion/data
     * Returns list of all factions
     */
    public function data() {
        // Horde faction has 0 / 0 standing 0 for all characters so is misleading
        $reputationData = Faction::orderBy('name', 'asc')->where('name', '!=', 'Horde')->get();
        return view('reputation.data')->with('reputation', $reputationData)->render();
    }

    /**
     * Handles GET requests to /reputstion/data/{id}
     * Returns list of characters with standings with that faction
     */
    public function factionData(Faction $faction) {
        return [
            'view' => view('reputation.partials.row')->with('reputation', $faction)->render(),
            'class' => $faction->getReputationClass()
        ];
    }
}
