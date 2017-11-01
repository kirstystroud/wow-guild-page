<?php

namespace App\Http\Controllers;

use Character;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function get(Request $request) {
        return redirect('/characters');
    }
}
