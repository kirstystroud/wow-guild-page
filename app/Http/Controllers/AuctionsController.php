<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auction;
use AuctionFilter;
use Character;

class AuctionsController extends Controller {

    /**
     * Handles GET requests to /auctions
     * Page load for auctions tab
     *
     * @return {view}
     */
    public function get() {
        return view('auctions.index');
    }

    /**
     * Handles GET requests to /auctions/data
     * Loads data on auctions tab
     *
     * @param  {AuctionFilter} $filters filter object constructed from request
     * @return {view}
     */
    public function data(AuctionFilter $filters) {
        // Apply filters from request data
        $auctions = Auction::filter($filters)->paginate(20);

        $characters = Character::select('id', 'name')->orderBy('name', 'asc')->get();

        return view('auctions.data')
            ->with('auctions', $auctions)
            ->with('characters', $characters)
            ->with('filters', $filters->filters());
    }

}
