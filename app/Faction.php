<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faction extends Model {

    public $timestamps = false;


    // Public relations

    public function reputations() {
        return $this->hasMany(Reputation::class);
    }

    public function getCharacters() {
        return $this->reputations()->orderBy('standing', 'desc')->orderBy('current', 'desc')->get();
    }


    /**
     * Get class for reputation panel
     */
    public function getReputationClass() {
        return 'reputation-panel-' . $this->reputations()->max('standing');
    }
}
