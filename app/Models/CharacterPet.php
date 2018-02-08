<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterPet extends Model {

    const FAVOURITE = 1;
    const NOT_FAVOURITE = 0;

    public $timestamps = false;

}
