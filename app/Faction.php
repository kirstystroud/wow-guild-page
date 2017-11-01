<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    public $timestamps = false;

    public function reputations() {
        return $this->hasMany(Reputation::class);
    }

    public function getCharacters() {
        return $this->reputations()->orderBy('standing', 'desc')->orderBy('current', 'desc')->get();
    }

    public function getReputationClass() {
        return 'reputation-panel-' . $this->reputations()->max('standing');
    }
}
