<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CharacterProfession extends Model
{
    public $timestamps = false;

    public static function character() {
        return $this->belongsTo(Character::class);
    }

    public static function profession() {
        return $this->belongsTo(Profession::class);
    }
}
