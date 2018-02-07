<?php

namespace App;

use Filter;
use Auction;

class AuctionFilter extends Filter {

    public function id($id) {
        return $this->_builder->where('auctions.id', $id);
    }

    public function item($item = '') {
        if (strlen($item)) {
            $this->_builder->whereHas('pet', function($q) use ($item) {
                $q->where('pets.name', 'LIKE', '%' . $item . '%');
            });
        }
        return $this->_builder;
    }

    public function sold($sold = false) {
        if ($sold && ($sold != 'false')) {
            $this->_builder->whereNotNull('sell_price');
        }
        return $this->_builder;
    }

    public function status($status) {
        if ($status != Auction::STATUS_UNKNOWN) {
            $this->_builder->where('status', $status);
        }
        return $this->_builder;
    }

    public function active($active) {
        if ($active && ($active != 'false')) {
            $this->_builder->where('time_left', '<>', Auction::TIME_LEFT_NONE);
        }
        return $this->_builder;
    }

    public function time($time) {
        if ($time != Auction::TIME_LEFT_UNKNOWN) {
            $this->_builder->where('time_left', $time);
        }
        return $this->_builder;
    }

    public function sort($sort){

        // Check which keys are set
        $sortKeys = array_keys($sort);

        switch($sortKeys[0]) {
            case 'item' :
                // handle item sorting - currently assumes has pet
                $this->_builder->join('pets', 'pets.id', 'auctions.pet_id')->orderBy('pets.name', $sort[$sortKeys[0]]);
                break;
            default :
                $this->_builder->orderBy($sortKeys[0], $sort[$sortKeys[0]]);
        }

        return $this->_builder;
    }

    public function defaultSort(){
        $this->_builder->orderBy('auctions.id', 'DESC');
    }
}