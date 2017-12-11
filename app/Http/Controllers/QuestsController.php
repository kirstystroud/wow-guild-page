<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestsController extends Controller
{
    public function get() {
        return view('quests');
    }
}
