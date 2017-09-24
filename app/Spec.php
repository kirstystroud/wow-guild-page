<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spec extends Model
{
    public $timestamps = false;

    const ROLE_DPS = 0;
    const ROLE_TANK = 1;
    const ROLE_HEALER = 2;

    public function getIconLocation() {
        return 'https://render-eu.worldofwarcraft.com/icons/56/' . $this->icon . '.jpg';
    }
}
