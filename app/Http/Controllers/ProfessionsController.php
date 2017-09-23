<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfessionsController extends Controller
{
    /**
     * Handles GET requests to /professions
     */
    public function get() {
        return view('professions');
    }
}
