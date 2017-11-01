<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    const NO_SPEC = '-';

    public $timestamps = false;

    // Need to figure out how to pull all specs in through API for this
    protected $tanks = [ 1, 2, 6, 10, 11, 12 ];

    protected $healers = [ 2, 5, 7, 10, 11 ];


    public function character_class() {
        return $this->belongsTo(CharacterClass::class, 'class_id');
    }

    public function race() {
        return $this->belongsTo(Race::class);
    }

    public function spec() {
        return $this->belongsTo(Spec::class);
    }

    public function title() {
        return $this->belongsTo(Title::class);
    }

    public function reputation() {
        return $this->hasMany(Reputation::class);
    }

    /**
     * Get class icon
     */
    public function getClassImg() {
        return 'https://render-eu.worldofwarcraft.com/icons/18/class_' . $this->character_class->id_ext . '.jpg';
    }

    /**
     * Get race icon
     */
    public function getRaceImg() {
        return 'https://render-eu.worldofwarcraft.com/icons/18/race_' . $this->race->id_ext . '_0.jpg';
    }

    /**
     * Get link to external character page
     */
    public function getLinkAddr() {
        // https://worldofwarcraft.com/en-gb/character/mazrigos/gribblez
        return 'https://worldofwarcraft.com/en-gb/character/' . env('WOW_REALM') . '/' . $this->name;
    }

    /**
     * Can this character tank
     */
    public function canTank() {
        return (bool) in_array($this->character_class->id_ext, $this->tanks);
    }

    /**
     * Can this character heal
     */
    public function canHeal() {
        return (bool) in_array($this->character_class->id_ext, $this->healers);
    }

    public function getTitle() {
        if (!$this->title_id) {
            return $this->name;
        }
        return str_replace('%s', $this->name, $this->title->name);
    }
}
