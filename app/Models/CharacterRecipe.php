<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterRecipe extends Model {

    public $timestamps = false;

    // Public relations

    /**
     * Define relation between character recipes and characters
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function character() {
        return $this->belongsTo(Character::class);
    }

    /**
     * Define relation between character recipes and recipes
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }
}
