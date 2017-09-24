<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CharacterClass extends Model
{
    public $timestamps = false;
    
    public function __construct() {
        $this->table = 'classes';
    }
}
