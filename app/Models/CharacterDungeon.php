<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterDungeon extends Model {

    public $timestamps = false;


    // Public relations

    public function character() {
        return $this->belongsTo(Character::class);
    }
}
