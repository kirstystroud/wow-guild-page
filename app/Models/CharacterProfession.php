<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterProfession extends Model {

    public $timestamps = false;


    // Public relations

    /**
     * Define relation between character professions and characters
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function character() {
        return $this->belongsTo(Character::class);
    }

    /**
     * Define relation between character professions and professions
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function profession() {
        return $this->belongsTo(Profession::class);
    }
}
