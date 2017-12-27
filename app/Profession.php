<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model {

    public $timestamps = false;


    // Public relations

    public function character_professions() {
        return $this->hasMany(CharacterProfession::class);
    }

    /**
     * Get list of characters with this profession ordered by skill
     * @return {Object}
     */
    public function getCharacterData() {
        $chars = Character::join('character_professions', 'characters.id', 'character_professions.character_id')
            ->where('profession_id', $this->id)
            ->where('character_professions.skill', '>', 1)
            ->orderBy('character_professions.skill', 'desc')
            ->orderBy('characters.name', 'asc')
            ->get();
        return $chars;
    }

    /**
     * Get the max skill any character has achieved in this profession
     * @return {int}
     */
    public function getMaxSkill() {
        return $this->character_professions()->max('skill');
    }

    /**
     * Get path to icon image
     * @return {string}
     */
    public function getIconLocation() {
        return 'https://render-eu.worldofwarcraft.com/icons/18/' . $this->icon . '.jpg';
    }
}
