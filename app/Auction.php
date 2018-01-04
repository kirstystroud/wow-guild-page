<?php

namespace App;

use Log;
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
     * Expire an auction, updating statuses based on last seen / previous status
     */
    public function expire() {
        if ($this->pet_id) {
            $name = $this->pet->name;
        } else {
            $name = $this->item->name;
        }

        // Track possible states
        $timedOut = false;
        $wentToBuyout = false;
        $sinceLastUpdated = strtotime(time()) - strtotime($this->date_last_seen);

        switch($this->time_left) {
            case Auction::TIME_LEFT_SHORT :
                // Short time left, have to assume expired
                $timedOut = true;
                break;
            case Auction::TIME_LEFT_MEDIUM :
                // 30min - 2hr to go
                if ($sinceLastUpdated > 1800) {
                    // not heard in last half hour, have to assume timed out
                    $timedOut = true;
                } else {
                    // Updated less than 30min ago, must have been bought out
                    $wentToBuyout = true;
                }
                break;
            case Auction::TIME_LEFT_LONG :
                // 2-12 hr to go
                if ($sinceLastUpdated > 7200) {
                    // Not heard in last two hours, have to assume timed out
                    $timedOut = true;
                } else {
                    $wentToBuyout = true;
                }
                break;
            case Auction::TIME_LEFT_VERY_LONG :
                // Over 12 hr to go
                if ($sinceLastUpdated > 43200) {
                    // Not heard in last 12 hours, have to assume timed out
                    $timedOut = true;
                } else {
                    $wentToBuyout = true;
                }
                break;
            default :
                throw new Exception('Unknown time left ' . $this->time_left);
        }

        if ($wentToBuyout) {
            // Update with buyout price
            $this->status = Auction::STATUS_SOLD;
            // Account for auctions with no buyout price
            $this->sell_price = $this->buyout ? $this->buyout : $this->bid;
            Log::debug('Auction for ' . $name . ' has been bought out for ' . $this->buyoutToGold());
        } elseif ($timedOut) {
            if ($this->status == Auction::STATUS_SELLING) {
                // Assume auction went for latest bid
                $this->status = Auction::STATUS_SOLD;
                $this->sell_price = $this->bid;
                Log::debug('Auction for ' . $name . ' has sold for ' . $this->bidToGold());
            } else {
                // Assume auction expired
                $this->status = Auction::STATUS_ENDED;
                Log::debug('Auction for ' . $name . ' has expired');
            }
        } else {
            // Should not get in here
        }

        // Update poll status
        $this->poll_status = Auction::POLL_STATUS_ENDED;
        $this->save();
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
