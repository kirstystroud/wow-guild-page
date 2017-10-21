<?php

namespace App\Http\Controllers;

use App\Character;

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
}
