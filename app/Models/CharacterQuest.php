<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterQuest extends Model {

    public $timestamps = false;

    // Public relations

    /**
     * Define relation between character quests and quests
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function quest() {
        return $this->belongsTo(Quest::class);
    }

    /**
     * Define relation between character quests and characters
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function character() {
        return $this->belongsTo(Character::class);
    }
}
