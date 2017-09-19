<?php

namespace App\Http\Controllers;

use App\Character;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function get(Request $request) {
        $characters = Character::orderBy('name', 'asc')->get();
        return view('welcome')->with('characters', $characters);
    }
}
