<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CharacterDungeon extends Model
{
    public $timestamps = false;

    public function character() {
        return $this->belongsTo(Character::class);
    }
}
