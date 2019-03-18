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

    /**
     * Define relation between character classes and characters
     *
     * @return {\Illuminate\Database\Eloquent\Relations\HasMany}
     */
    public function characters() {
        return $this->hasMany(Character::class, 'class_id');
    }
}
