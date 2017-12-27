<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CharacterRecipe extends Model {

    public $timestamps = false;


    // Public relations

    public function character() {
        return $this->belongsTo(Character::class);
    }

    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }
}
