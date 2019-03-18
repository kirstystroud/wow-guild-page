<?php

namespace App\Models;

use Log;
use AuctionFilter;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model {

    const STATUS_UNKNOWN = -5;
    const STATUS_ACTIVE = 0;
    const STATUS_SELLING = 1;
    const STATUS_SOLD = 2;
    const STATUS_ENDED = -1;

    const POLL_STATUS_ENDED = -1;
    const POLL_STATUS_PENDING = 0;
    const POLL_STATUS_PROCESSED = 1;

    const TIME_LEFT_UNKNOWN = -5;
    const TIME_LEFT_NONE = -1;
    const TIME_LEFT_VERY_LONG = 0;
    const TIME_LEFT_LONG = 1;
    const TIME_LEFT_MEDIUM = 2;
    const TIME_LEFT_SHORT = 3;


    // Public relations

    /**
     * Define relation between auctions and pets
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function pet() {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Define relation between auctions and items
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function item() {
        return $this->belongsTo(Item::class);
    }


    // Enable filtering

    public function scopeFilter($builder, AuctionFilter $filters) {
        return $filters->apply($builder);
    }

    // Static helper functions

    public static function getStatuses() {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_SELLING => 'Selling',
            self::STATUS_SOLD => 'Sold',
            self::STATUS_ENDED => 'Ended',
            self::STATUS_UNKNOWN => '-'
        ];
    }

    public static function getTimeRemaining() {
        return [
            self::TIME_LEFT_VERY_LONG => 'Very Long',
            self::TIME_LEFT_LONG => 'Long',
            self::TIME_LEFT_MEDIUM => 'Medium',
            self::TIME_LEFT_SHORT => 'Short',
            self::TIME_LEFT_NONE => '-',
            self::TIME_LEFT_UNKNOWN => '-'
        ];
    }


    // Helper functions

    /**
     * Convert bid to human-readable with icons
     *
     * @return {string}
     */
    public function bidToGoldFormatted() {
        return $this->formatMoneyString($this->bidToGold());
    }

    /**
     * Convert buyout to human-readable with icons
     *
     * @return {string}
     */
    public function buyoutToGoldFormatted() {
        return $this->formatMoneyString($this->buyoutToGold());
    }

    /**
     * Convert sell price to human-readable with icons
     *
     * @return {string}
     */
    public function sellPriceToGoldFormatted() {
        return $this->formatMoneyString($this->sellPriceToGold());
    }

    /**
     * Calculate average sell price and return in a human-readable format with icons
     */
    public function averageSellPriceToGoldFormatted() {
        $averageSellPrice = static::select([\DB::raw('AVG(sell_price) AS avg_sell_price')])->where('pet_id', $this->pet_id)->first();
        return $this->formatMoneyString($this->intToGold(round($averageSellPrice['avg_sell_price'])));
    }

    /**
     * Convert bid into human readable gold silver copper
     *
     * @return {string}
     */
    public function bidToGold() {
        return $this->intToGold($this->bid);
    }

    /**
     * Convert bid into human readable gold silver copper
     *
     * @return {string}
     */
    public function buyoutToGold() {
        return $this->intToGold($this->buyout);
    }

    /**
     * Convert sell price into human readable gold silver copper
     *
     * @return {string}
     */
    public function sellPriceToGold() {
        return $this->intToGold($this->sell_price);
    }

    /**
     * Get human-friendly item name
     *
     * @return {string}
     */
    public function itemName() {
        if ($this->pet_id) {
            $name = $this->pet->name;
        } else if ($this->pet_name) {
            $name = $this->pet_name;
        } else {
            $name = $this->item->name;
        }

        return $name;
    }

    /**
     * Get human-readable time left
     *
     * @return {string}
     */
    public function timeLeft() {
        return self::getTimeRemaining()[$this->time_left];
    }

    /**
     * Get human-readable status
     *
     * @return {string}
     */
    public function getStatus() {
        return self::getStatuses()[$this->status];
    }

    /**
     * Get a list of past auctions for this item which have sold in the last month
     */
    public function getPreviouslySold() {
        $cutoff = \Carbon\Carbon::now()->subMonth();
        return static::where('pet_id', $this->pet_id)
                    ->where('updated_at', '>=', $cutoff)
                    ->whereNotNull('sell_price')
                    ->orderBy('sell_price', 'DESC')
                    ->get();
    }

    /**
     * Expire an auction, updating statuses based on last seen / previous status
     */
    public function expire() {
        $name = $this->itemName();

        // Track possible states
        $timedOut = false;
        $wentToBuyout = false;
        $sinceLastUpdated = strtotime(time()) - strtotime($this->date_last_seen);

        switch($this->time_left) {
            case self::TIME_LEFT_SHORT :
                // Short time left, have to assume expired
                $timedOut = true;
                break;
            case self::TIME_LEFT_MEDIUM :
                // 30min - 2hr to go
                if ($sinceLastUpdated > 1800) {
                    // not heard in last half hour, have to assume timed out
                    $timedOut = true;
                } else {
                    // Updated less than 30min ago, must have been bought out
                    $wentToBuyout = true;
                }
                break;
            case self::TIME_LEFT_LONG :
                // 2-12 hr to go
                if ($sinceLastUpdated > 7200) {
                    // Not heard in last two hours, have to assume timed out
                    $timedOut = true;
                } else {
                    $wentToBuyout = true;
                }
                break;
            case self::TIME_LEFT_VERY_LONG :
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
            $this->status = self::STATUS_SOLD;
            // Account for auctions with no buyout price
            $this->sell_price = $this->buyout ? $this->buyout : $this->bid;
            Log::debug('Auction for ' . $name . ' has been bought out for ' . $this->buyoutToGold());
        } elseif ($timedOut) {
            if ($this->status == self::STATUS_SELLING) {
                // Assume auction went for latest bid
                $this->status = self::STATUS_SOLD;
                $this->sell_price = $this->bid;
                Log::debug('Auction for ' . $name . ' has sold for ' . $this->bidToGold());
            } else {
                // Assume auction expired
                $this->status = self::STATUS_ENDED;
                Log::debug('Auction for ' . $name . ' has expired');
            }
        } else {
            // Should not get in here
        }

        // Update poll status
        $this->poll_status = self::POLL_STATUS_ENDED;
        $this->time_left = self::TIME_LEFT_NONE;
        $this->save();
    }

    /**
     * Convert an integer into human readable gold silver copper
     *
     * @param  {int} $value
     * @return {string}
     */
    protected function intToGold($value) {

        if (is_null($value)) {
            return '';
        }

        $rtn = '';

        $gold = floor($value / 10000);
        $value -= (10000 * $gold);

        $silver = floor($value / 100);
        $value -= (100 * $silver);

        $copper = $value;

        if ($gold) {
            $rtn .= number_format($gold) . ' Gold, ';
        }

        if ($silver || $gold) {
            $rtn .= $silver . ' Silver, ';
        }

        $rtn .= $copper . ' Copper ';

        return trim($rtn);
    }

    /**
     * Replace text with icons for front end
     *
     * @param  {string} $input
     * @return {string}
     */
    protected function formatMoneyString($input) {
        $result = str_replace('Gold,', '<span class="span-gold"><img src="https://worldofwarcraft.com/static/components/GameTooltip/GameTooltip-gold.gif"></img></span>', $input);
        $result = str_replace('Silver,', '<span class="span-silver"><img src="https://worldofwarcraft.com/static/components/GameTooltip/GameTooltip-silver.gif"></img></span>', $result);
        $result = str_replace('Copper', '<span class="span-copper"><img src="https://worldofwarcraft.com/static/components/GameTooltip/GameTooltip-copper.gif"></img></span>', $result);
        return $result;
    }
}
