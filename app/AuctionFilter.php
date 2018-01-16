<?php

namespace App;

use Filter;

class AuctionFilter extends Filter {

    public function id($id) {
        return $this->_builder->where('auctions.id', $id);
    }

    public function status($status) {
        if ($status != Auction::STATUS_UNKNOWN) {
            $this->_builder->where('status', $status);
        }
        return $this->_builder;
    }

    public function time($time) {
        if ($time != Auction::TIME_LEFT_UNKNOWN) {
            $this->_builder->where('time_left', $time);
        }
        return $this->_builder;
    }

    public function sort($sortBy){
        $sortBy = explode(" ", $sortBy);
        $this->_builder->orderBy($sortBy[0], $sortBy[1]);
        return $this->_builder;
    }

    public function defaultSort(){
        $this->_builder->orderBy('auctions.id', 'DESC');
    }
}