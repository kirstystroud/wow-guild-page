<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;

class Dungeon extends Model {

    // Minimum character required to run a dungeon
    const MIN_REQUIRED = 5;

    // Status mappings for front end
    const STATUS_UNAVAILABLE = 0;
    const STATUS_PARTIALLY_AVAILABLE = 1;
    const STATUS_AVAILABLE = 2;

    // Dungeon type mappings
    const TYPE_UNKNOWN = 0;
    const TYPE_DUNGEON = 1;
    const TYPE_RAID = 2;

    public $timestamps = false;


    // Public relations

    public function character_dungeons() {
        return $this->hasMany(CharacterDungeon::class);
    }

    public function getHeading() {
        return $this->name . ' (' . $this->min_level . ' - ' . $this->max_level . ')';
    }


    // Helper functions

    /**
     * Get characters who are able to run this dungeon
     * @return {Object}
     */
    public function getAvailableChars() {
        $chars = Character::where('level',  '>=', $this->min_level)
            ->where('level',  '<=', $this->max_level)
            ->orderBy('level', 'asc')
            ->get();
        return $chars;
    }

    /** 
     * Get summary of characters who have run this raid
     * @return {Object}
     */
    public function getCharacterRaidData() {
        return CharacterDungeon::where('dungeon_id', $this->id)
            ->where(function($query) {
                return $query->where('lfr', '>', 0)
                    ->orWhere('normal', '>', 0)
                    ->orWhere('heroic', '>', 0)
                    ->orWhere('mythic', '>', 0);
            })
            ->get();
    }

    /**
     * Get class to be applied to this panel in dungeons view
     * @return {string}
     */
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

    /**
     * Get status of this dungeon based on the characters who are able to run it
     */
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
