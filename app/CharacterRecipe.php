<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CharacterRecipe extends Model
{
    public $timestamps = false;

    public function character() {
        return $this->belongsTo('\App\Character');
    }

    public function recipe() {
        return $this->belongsTo('\App\Recipe');
    }
}
