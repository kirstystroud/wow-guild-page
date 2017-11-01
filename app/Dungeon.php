<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dungeon extends Model
{
    const MIN_REQUIRED = 5;

    const STATUS_UNAVAILABLE = 0;
    const STATUS_PARTIALLY_AVAILABLE = 1;
    const STATUS_AVAILABLE = 2;

    const TYPE_UNKNOWN = 0;
    const TYPE_DUNGEON = 1;
    const TYPE_RAID = 2;

    public $timestamps = false;

    public function getHeading() {
        return $this->name . ' (' . $this->min_level . ' - ' . $this->max_level . ')';
    }

    public function getAvailableChars() {
        $chars = Character::where('level',  '>=', $this->min_level)
            ->where('level',  '<=', $this->max_level)
            ->orderBy('level', 'asc')
            ->get();
        return $chars;
    }

    public function getPanelClass() {
        $status = $this->getStatus();
        switch($status) {
            case self::STATUS_UNAVAILABLE :
                return 'panel-danger';
            case self::STATUS_PARTIALLY_AVAILABLE :
                return 'panel-warning';
            case self::STATUS_AVAILABLE :
                return 'panel-success';
            default :
                return 'panel-danger';
        }
    }

    public function getStatus() {
        $chars = $this->getAvailableChars();
        if (!count($chars)) {
            return self::STATUS_UNAVAILABLE;
        }

        // Not enough chars
        if (count($chars) < self::MIN_REQUIRED) {
            return self::STATUS_PARTIALLY_AVAILABLE;
        }

        // Check enough tanks and healers
        $healers = 0;
        $tanks = 0;
        foreach ($chars as $char) {
            if ($char->canHeal()) $healers++;
            if ($char->canTank()) $tanks++;
        }

        // Don't have either tanks or healers
        if (!$healers || !$tanks) {
            return self::STATUS_PARTIALLY_AVAILABLE;
        }

        // Have over two valid chars
        if (($tanks + $healers) > 2) {
            return self::STATUS_AVAILABLE;
        }

        // Have exactly two, make sure not same char
        if (($healers + $tanks) == 2) {
            foreach ($chars as $char) {
                if (($char->canHeal()) && ($char->canTank())) {
                    return self::STATUS_PARTIALLY_AVAILABLE;
                }
            }
        }

        return self::STATUS_AVAILABLE;
    }
}
