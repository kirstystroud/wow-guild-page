<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faction extends Model {

    public $timestamps = false;

    // Public relations

    /**
     * Define relation between factions and reputations
     *
     * @return {\Illuminate\Database\Eloquent\Relations\HasMany}
     */
    public function reputations() {
        return $this->hasMany(Reputation::class);
    }

    /**
     * Get a list of characters aligned with this faction
     *
     * @return {array}
     */
    public function getCharacters() {
        return $this->reputations()->orderBy('standing', 'desc')->orderBy('current', 'desc')->get();
    }


    /**
     * Get class for reputation panel
     *
     * @return {string}
     */
    public function getReputationClass() {
        return 'reputation-panel-' . $this->reputations()->max('standing');
    }
}
