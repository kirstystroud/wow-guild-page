<?php

namespace App;

use DB;
use Filter;
use Auction;

class AuctionFilter extends Filter {

    /**
     * Initialise filters
     *
     * @return {Builder}
     */
    protected function init() {
        $this->_builder->select([
            'pets.name AS pet_name',
            'sell_price',
            'buyout',
            'bid',
            'status',
            'time_left'
        ])
            ->join('pets', 'pets.id', 'auctions.pet_id');
        return $this->_builder;
    }

    /**
     * Filter by id
     *
     * @param  {int} $id
     * @return {Builder}
     */
    public function id($id) {
        return $this->_builder->where('auctions.id', $id);
    }

    /**
     * Filter by item name
     *
     * @param  {string} $item
     * @return {Builder}
     */
    public function item($item = '') {
        if (strlen($item)) {
            $this->_builder->where('pets.name', 'LIKE', '%' . $item . '%');
        }
        return $this->_builder;
    }

    /**
     * Only items which have sold
     *
     * @param  {string|bool} $sold
     * @return {Builder}
     */
    public function sold($sold = false) {
        if ($sold && ($sold != 'false')) {
            $this->_builder->whereNotNull('sell_price');
        }
        return $this->_builder;
    }

    /**
     * Filter by status
     *
     * @param  {int} $status
     * @return {Builder}
     */
    public function status($status) {
        if ($status != Auction::STATUS_UNKNOWN) {
            $this->_builder->where('status', $status);
        }
        return $this->_builder;
    }

    /**
     * Only currently active
     *
     * @param  {string} $active
     * @return {Builder}
     */
    public function active($active) {
        if ($active && ($active != 'false')) {
            $this->_builder->where('time_left', '<>', Auction::TIME_LEFT_NONE);
        }
        return $this->_builder;
    }

    /**
     * Only the cheapest of each item
     *
     * @param  {string} $cheapest
     * @return {Builder}
     */
    public function cheapest($cheapest) {
        if ($cheapest && ($cheapest != 'false')) {
            $this->_builder->select([
                'auctions.pet_id',
                DB::raw('min(buyout) as buyout'),
                DB::raw('min(bid) as bid'),
                DB::raw('"' . Auction::STATUS_UNKNOWN . '" AS status'),
                DB::raw('"' . Auction::TIME_LEFT_UNKNOWN . '" AS time_left')
            ])->groupBy('auctions.pet_id');
        }
        return $this->_builder;
    }

    /**
     * Filter by time left
     *
     * @param  {int} $time
     * @return {Builder}
     */
    public function time($time) {
        if ($time != Auction::TIME_LEFT_UNKNOWN) {
            $this->_builder->where('time_left', $time);
        }
        return $this->_builder;
    }

    /**
     * Only not owned by the current character
     *
     * @param  {string} $characterId
     * @return {Builder}
     */
    public function notowned($characterId) {
        if ($characterId && $characterId !== "0") {
            $this->_builder->leftJoin('character_pets', function($q) use ($characterId) {
                // Join on specific character id
                $q->on('auctions.pet_id', '=', 'character_pets.pet_id')
                    ->where('character_pets.character_id', '=', $characterId);
            })
            // Expect no join
            ->whereNull('character_pets.id')
            ->whereNotNull('auctions.pet_id');
        }
        return $this->_builder;
    }

    /**
     * Apply sorting
     *
     * @param  {array} $sort format { 'column_name' => 'order' }
     * @return {Builder}
     */
    public function sort($sort) {
        // Check which keys are set
        $sortKeys = array_keys($sort);

        switch($sortKeys[0]) {
            case 'item' :
                // handle item sorting - currently assumes has pet
                $this->_builder->orderBy('pets.name', $sort[$sortKeys[0]]);
                break;
            case 'sell_price' :
                if (!isset($this->filters()['cheapest']) || !$this->filters()['cheapest'] || ($this->filters()['cheapest'] === 'false')) {
                    $this->_builder->orderBy($sortKeys[0], $sort[$sortKeys[0]]);
                }
                break;
            default :
                $this->_builder->orderBy($sortKeys[0], $sort[$sortKeys[0]]);
        }

        return $this->_builder;
    }

    /**
     * Default sorting
     *
     * @return {void}
     */
    public function defaultSort() {
        $active = $this->isFilterSet('active');
        $cheapest = $this->isFilterSet('cheapest');

        switch(true) {
            case !$active && !$cheapest :
                // Nothing set, order by id
                $this->_builder->orderBy('auctions.id', 'DESC');
                break;
            case $active && !$cheapest :
                // Active, order by recent
                $this->_builder->orderBy('pets.name');
                break;
            case $cheapest :
                // Cheapest, order by sell price
                $this->_builder->orderBy('buyout');
                break;
            default :
                $this->_builder->orderBy('auctions.pet_id', 'DESC'); //always available
        }
    }
}
