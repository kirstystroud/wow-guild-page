<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quest extends Model {

    public $timestamps = false;


    // Public relations

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function character_quests() {
        return $this->hasMany(CharacterQuest::class);
    }
}
