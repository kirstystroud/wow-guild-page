<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auction;
use AuctionFilter;
use Character;

class AuctionsController extends Controller {
    
    public function get() {
        return view('auctions.index');
    }

    public function data(AuctionFilter $filters) {

        $auctions = Auction::filter($filters)->paginate(20);

        $characters = $characters = Character::orderBy('name', 'asc')->get();

        return view('auctions.data')
            ->with('auctions', $auctions)
            ->with('characters', $characters)
            ->with('filters', $filters->filters());
    }

}
