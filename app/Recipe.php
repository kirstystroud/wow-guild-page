<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public $timestamps = false;

    public function profession() {
        return $this->belongsTo('\App\Profession');
    }

    public function character_recipes() {
        return $this->hasMany('\App\CharacterRecipe');
    }
}
