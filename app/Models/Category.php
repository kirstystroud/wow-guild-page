<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    public $timestamps = false;

    // Public relations

    public function quests() {
        return $this->hasMany(Quest::class);
    }
}
