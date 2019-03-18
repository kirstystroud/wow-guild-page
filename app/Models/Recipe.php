<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model {

    public $timestamps = false;

    // Public relations

    /**
     * Define relation between recipes and professions
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function profession() {
        return $this->belongsTo(Profession::class);
    }

    /**
     * Define relation between recipes and character recipes
     *
     * @return {\Illuminate\Database\Eloquent\Relations\HasMany}
     */
    public function character_recipes() {
        return $this->hasMany(CharacterRecipe::class);
    }
}
