<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CharacterProfession extends Model
{
    public $timestamps = false;

    public static function character() {
        return $this->belongsTo('\App\Character');
    }

    public static function profession() {
        return $this->belongsTo('App\Profession');
    }
}
