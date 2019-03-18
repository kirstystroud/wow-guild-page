<?php

namespace App\Http\Controllers;

use Character;
use Meta;
use Illuminate\Http\Request;

class HomeController extends Controller {

    /**
     * Redirect calls to / to /characters
     *
     * @return {redirect}
     */
    public function get() {
        return redirect('/characters');
    }

    /**
     * Handles GET requests to /tabard
     * Loads required meta information for guild tabard
     *
     * @return {string} json encoded meta information for tabard
     */
    public function tabardData() {
        return Meta::getMeta(Meta::KEY_TABARD);
    }

}
