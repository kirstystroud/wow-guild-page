<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quest extends Model {

    public $timestamps = false;

    // Public relations

    /**
     * Define relation between quests and categories
     *
     * @return {\Illuminate\Database\Eloquent\Relations\BelongsTo}
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }

    /**
     * Define relation between quests and character quests
     *
     * @return {\Illuminate\Database\Eloquent\Relations\HasMany}
     */
    public function character_quests() {
        return $this->hasMany(CharacterQuest::class);
    }
}
