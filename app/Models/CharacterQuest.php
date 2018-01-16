<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterQuest extends Model {

    public $timestamps = false;


    // Public relations

    public function quest() {
        return $this->belongsTo(Quest::class);
    }

    public function character() {
        return $this->belongsTo(Character::class);
    }
}
