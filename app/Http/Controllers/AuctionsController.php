<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuctionsController extends Controller {
    
    public function get() {
        return view('auctions');
    }

}
