<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    public $timestamps = false;

    // Public relations

    /**
     * Define relation between catagories and quests
     *
     * @return {\Illuminate\Database\Eloquent\Relations\HasMany}
     */
    public function quests() {
        return $this->hasMany(Quest::class);
    }

    // Helper functions

    /**
     * Get a list of characters and how many quests they have completed for a category
     *
     * @return {array}
     */
    public function getCharactersByQuestsCompleted() {
        return CharacterQuest::select('characters.name', 'characters.id AS character_id', \DB::raw('COUNT(DISTINCT quests.name) as count'))
            ->join('quests', 'quest_id', 'quests.id')
            ->join('characters', 'character_id', 'characters.id')
            ->where('quests.category_id', $this->id)
            ->groupBy('characters.name')
            ->groupBy('characters.id')
            ->orderBy('characters.name')
            ->get();
    }
}
