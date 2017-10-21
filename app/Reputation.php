<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reputation extends Model
{
    const STANDING_HATED      = 0;
    const STANDING_HOSTILE    = 1;
    const STANDING_UNFRIENDLY = 2;
    const STANDING_NEUTRAL    = 3;
    const STANDING_FRIENDLY   = 4;
    const STANDING_HONORED    = 5;
    const STANDING_REVERED    = 6;
    const STANDING_EXALTED    = 7;

    public $timestamps = 0;

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

    public function faction() {
        return $this->belongsTo('\App\Faction');
    }

    public function character() {
        return $this->belongsTo('\App\Character');
    }

    public function getProgress() {
        return $this->max == 0 ? '100%' : floor(100 * ($this->current / $this->max)) . '%';
    }
}