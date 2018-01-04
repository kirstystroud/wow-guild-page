<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model {

    const STATUS_ACTIVE = 0;
    const STATUS_SELLING = 1;
    const STATUS_SOLD = 2;
    const STATUS_ENDED = -1;

    const POLL_STATUS_ENDED = -1;
    const POLL_STATUS_PENDING = 0;
    const POLL_STATUS_PROCESSED = 1;

    const TIME_LEFT_VERY_LONG = 0;
    const TIME_LEFT_LONG = 1;
    const TIME_LEFT_MEDIUM = 2;
    const TIME_LEFT_SHORT = 3;


    // Public relations

    public function pet() {
        return $this->belongsTo(Pet::class);
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }


    // Helper functions

    /**
     * Convert bid into human readable gold silver copper
     * @return {string}
     */
    public function bidToGold() {
        return $this->intToGold($this->bid);
    }

    /**
     * Convert bid into human readable gold silver copper
     * @return {string}
     */
    public function buyoutToGold() {
        return $this->intToGold($this->buyout);
    }

    /**
     * Convert an integer into human readable gold silver copper
     * @param {int} $value
     * @return {string}
     */
    protected function intToGold($value) {
        $rtn = '';

        $gold = floor($value / 10000);
        $value -= (10000 * $gold);

        $silver = floor($value / 100);
        $value -= (100 * $silver);

        $copper = $value;

        if ($gold) {
            $rtn .= $gold . ' Gold, ';
        }

        if ($silver || $gold) {
            $rtn .= $silver . ' Silver, ';
        }

        $rtn .= $copper . ' Copper ';

        return trim($rtn);
    }
}
