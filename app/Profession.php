<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    public $timestamps = false;

    /**
     * Get list of characters with this profession ordered by skill
     */
    public function getCharacterData() {
        $chars = Character::join('character_professions', 'characters.id', 'character_professions.character_id')
            ->where('profession_id', $this->id)
            ->orderBy('character_professions.skill', 'desc')
            ->orderBy('characters.name', 'asc')
            ->get();
        return $chars;
    }
}
