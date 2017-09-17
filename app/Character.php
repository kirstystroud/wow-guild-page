<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    public $timestamps = false;

    protected $classNames = [
        1 => 'Warrior',
        2 => 'Paladin',
        3 => 'Hunter',
        4 => 'Rogue',
        5 => 'Priest',
        6 => 'Death Knight',
        7 => 'Shaman',
        8 => 'Mage',
        9 => 'Warlock',
        10 => 'Monk',
        11 => 'Druid',
        12 => 'Demon Hunter'
    ];

    protected $raceNames = [
        2 => 'Orc',
        5 => 'Undead',
        6 => 'Tauren',
        8 => 'Troll',
        9 => 'Goblin',
        10 => 'Blood Elf',
        26 => 'Pandaren'
    ];

    /**
     * Get class icon
     */
    public function getClassImg() {
        return 'https://render-eu.worldofwarcraft.com/icons/18/class_' . $this->class . '.jpg';
    }

    /**
     * Get human-friendly class-name
     */
    public function getClassName() {
        return $this->classNames[$this->class];
    }

    /**
     * Get spec icon if available
     */
    public function getSpecImg() {
        return $this->spec;
    }

    /**
     * Get race icon
     */
    public function getRaceImg() {
        return 'https://render-eu.worldofwarcraft.com/icons/18/race_' . $this->race . '_0.jpg';
    }

    public function getRaceName() {
        return $this->raceNames[$this->race];
    }
}
