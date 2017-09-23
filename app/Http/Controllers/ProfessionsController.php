<?php

namespace App\Http\Controllers;

use App\Profession;

use Illuminate\Http\Request;

class ProfessionsController extends Controller
{
    /**
     * Handles GET requests to /professions
     */
    public function get() {
        $professions = Profession::orderBy('name', 'asc')->get();
        return view('professions')->with('professions', $professions);
    }
}
