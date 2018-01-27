<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auction;
use AuctionFilter;

class AuctionsController extends Controller {
    
    public function get() {
        return view('auctions.index');
    }

    public function data(AuctionFilter $filters) {

        $auctions = Auction::filter($filters)->paginate(20);

        return view('auctions.data')->with('auctions', $auctions)->with('filters', $filters->filters());
    }

}
