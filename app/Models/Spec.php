<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spec extends Model {

    public $timestamps = false;

    // What roles can this spec perform
    const ROLE_DPS = 0;
    const ROLE_TANK = 1;
    const ROLE_HEALER = 2;

    /**
     * Get icon location for this spec
     *
     * @return {string}
     */
    public function getIconLocation() {
        return 'https://render-eu.worldofwarcraft.com/icons/56/' . $this->icon . '.jpg';
    }
}
