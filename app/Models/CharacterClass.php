<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterClass extends Model {

    public $timestamps = false;

    /**
     * Initialise table in constructor
     *
     * @return {void}
     */
    public function __construct() {
        $this->table = 'classes';
    }


    // Public relations

    public function characters() {
        return $this->hasMany(Character::class, 'class_id');
    }
}
