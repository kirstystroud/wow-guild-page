<?php

namespace App\Http\Controllers;

use Character;
use Illuminate\Http\Request;

class HomeController extends Controller {
    /**
     * Redirect calls to / to /characters
     */
    public function get(Request $request) {
        return redirect('/characters');
    }
}
