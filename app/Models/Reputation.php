<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reputation extends Model {

    // List of reputation standings
    const STANDING_HATED      = 0;
    const STANDING_HOSTILE    = 1;
    const STANDING_UNFRIENDLY = 2;
    const STANDING_NEUTRAL    = 3;
    const STANDING_FRIENDLY   = 4;
    const STANDING_HONORED    = 5;
    const STANDING_REVERED    = 6;
    const STANDING_EXALTED    = 7;

    public $timestamps = 0;

    /**
     * Get human-friendly mappings for standings
     *
     * @return {array}
     */
    public static function getStandings() {
        return [
            self::STANDING_HATED => 'Hated',
            self::STANDING_HOSTILE => 'Hostile',
            self::STANDING_UNFRIENDLY => 'Unfriendly',
            self::STANDING_NEUTRAL => 'Neutral',
            self::STANDING_FRIENDLY => 'Friendly',
            self::STANDING_HONORED => 'Honored',
            self::STANDING_REVERED => 'Revered',
            self::STANDING_EXALTED => 'Exalted'
        ];
    }


    // Public relations

    /**
     * Define relation between reputations and factions
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function faction() {
        return $this->belongsTo(Faction::class);
    }

    /**
     * Define relation between reputations and characters
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function character() {
        return $this->belongsTo(Character::class);
    }


    // Helper functions

    /**
     * Get progress through this standing as a percentage
     *
     * @return {string}
     */
    public function getProgress() {
        return $this->max == 0 ? '100%' : floor(100 * ($this->current / $this->max)) . '%';
    }
}
