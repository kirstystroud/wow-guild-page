<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auction;
use AuctionFilter;
use Character;

class AuctionsController extends Controller {
    
    public function get() {
        $characters = Character::orderBy('name', 'asc')->get();
        return view('auctions.index')->with('characters', $characters);
    }

    public function data(AuctionFilter $filters) {

        $auctions = Auction::filter($filters)->paginate(20);

        if (isset($filters->filters()['character']) && $filters->filters()['character']) {
            $characterId = $filters->filters()['character'];
        } else {
            $characterId = false;
        }

        if ($characterId > 0) {
            $character = Character::find($characterId);
        } else {
            $character = false;
        }

        return view('auctions.data')
            ->with('auctions', $auctions)
            ->with('character', $character)
            ->with('filters', $filters->filters());
    }

}
