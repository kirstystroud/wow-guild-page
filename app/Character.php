<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    const NO_SPEC = '-';

    public $timestamps = false;

    protected $tanks = [ 1, 2, 6, 10, 11, 12 ];

    protected $healers = [ 2, 5, 7, 10, 11 ];


    public function character_class() {
        return $this->belongsTo('\App\CharacterClass', 'class_id');
    }

    public function race() {
        return $this->belongsTo('\App\Race');
    }

    public function spec() {
        return $this->belongsTo('\App\Spec');
    }

    /**
     * Get class icon
     */
    public function getClassImg() {
        return 'https://render-eu.worldofwarcraft.com/icons/18/class_' . $this->class_id . '.jpg';
    }

    /**
     * Get race icon
     */
    public function getRaceImg() {
        return 'https://render-eu.worldofwarcraft.com/icons/18/race_' . $this->race->id_ext . '_0.jpg';
    }

    /**
     * Can this character tank
     */
    public function canTank() {
        return (bool) in_array($this->class_id, $this->tanks);
    }

    /**
     * Can this character heal
     */
    public function canHeal() {
        return (bool) in_array($this->class_id, $this->healers);
    }
}
