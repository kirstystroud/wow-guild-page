<?php

namespace App\Http\Controllers;

use App\Character;
use App\Faction;

use Illuminate\Http\Request;

class ReputationController extends Controller
{
    /**
     * Handles GET requests to /professions
     */
    public function get() {
        $characters = Character::orderBy('name', 'asc')->get();
        return view('reputation')->with('characters', $characters);
    }

    public function data() {
        $reputationData = Faction::orderBy('name', 'asc')->get();
        return view('partials.reputation')->with('reputation', $reputationData)->render();
    }
}
