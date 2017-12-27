<?php

namespace App\Http\Controllers;

use Character;
use Meta;
use Illuminate\Http\Request;

class HomeController extends Controller {

    /**
     * Redirect calls to / to /characters
     */
    public function get(Request $request) {
        return redirect('/characters');
    }

    /**
     * Handles GET requests to /tabard
     * Loads required meta information for guild tabard
     */
    public function tabardData() {
        return Meta::getMeta(Meta::KEY_TABARD);
    }

}
