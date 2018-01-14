<?php

namespace App;

use Filter;

class AuctionFilter extends Filter {

    public function id($id) {
        return $this->_builder->where('auctions.id', $id);
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