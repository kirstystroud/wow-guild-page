<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterDungeon extends Model {

    public $timestamps = false;


    // Public relations

    /**
     * Define relation between character dungeons and characters
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function character() {
        return $this->belongsTo(Character::class);
    }
}
