<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterProfession extends Model {

    public $timestamps = false;


    // Public relations

    public function character() {
        return $this->belongsTo(Character::class);
    }

    public function profession() {
        return $this->belongsTo(Profession::class);
    }
}
