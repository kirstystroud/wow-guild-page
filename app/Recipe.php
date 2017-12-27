<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model {

    public $timestamps = false;


    // Public relations

    public function profession() {
        return $this->belongsTo(Profession::class);
    }

    public function character_recipes() {
        return $this->hasMany(CharacterRecipe::class);
    }
}
