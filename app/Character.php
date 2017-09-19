<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    const NO_SPEC = '-';

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

    protected $tanks = [ 1, 2, 6, 10, 11, 12 ];

    protected $healers = [ 2, 5, 7, 10, 11 ];

    protected $specImages = [
        /* Warrior */
        1 => [
            'Arms' => 'https://render-eu.worldofwarcraft.com/wow/icons/56/ability_warrior_savageblow.jpg',
            'Fury' => 'https://render-eu.worldofwarcraft.com/wow/icons/56/ability_warrior_innerrage.jpg',
            'Protection' => 'https://render-eu.worldofwarcraft.com/icons/56/ability_warrior_defensivestance.jpg',
        ],
        /* Paladin */
        2 => [
           'Holy' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_holy_holybolt.jpg',
           'Protection' => 'https://render-eu.worldofwarcraft.com/icons/56/ability_paladin_shieldofthetemplar.jpg',
           'Retribution' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_holy_auraoflight.jpg', 
        ],
        /* Hunter */
        3 => [
            'Beast Mastery' => 'https://render-eu.worldofwarcraft.com/icons/56/ability_hunter_bestialdiscipline.jpg',
            'Marksmanship' => 'https://render-eu.worldofwarcraft.com/icons/56/ability_hunter_focusedaim.jpg',
            'Survival' => 'https://render-eu.worldofwarcraft.com/icons/56/ability_hunter_camouflage.jpg', 
        ],
        /* Rogue */
        4 => [
            'Assassination' => 'https://render-eu.worldofwarcraft.com/icons/56/ability_rogue_deadlybrew.jpg',
            'Outlaw' => 'https://render-eu.worldofwarcraft.com/icons/56/inv_sword_30.jpg',
            'Subtlety' => 'https://render-eu.worldofwarcraft.com/icons/56/ability_stealth.jpg', 
        ],
        /* Priest */
        5 => [
            'Discipline' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_holy_powerwordshield.jpg',
            'Holy' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_holy_guardianspirit.jpg',
            'Shadow' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_shadow_shadowwordpain.jpg',
        ],
        /* Death Knight */
        6 => [
            'Blood' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_deathknight_bloodpresence.jpg',
            'Frost' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_deathknight_frostpresence.jpg',
            'Unholy' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_deathknight_unholypresence.jpg',
        ],
        /* Shaman */
        7 => [
            'Elemental' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_nature_lightning.jpg',
            'Enhancement' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_shaman_improvedstormstrike.jpg',
            'Restoration' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_nature_magicimmunity.jpg',
        ],
        /* Mage */
        8 => [
            'Arcane' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_holy_magicalsentry.jpg',
            'Fire' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_fire_firebolt02.jpg',
            'Frost' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_frost_frostbolt02.jpg',
        ],
        /* Warlock */
        9 => [
            'Affliction' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_shadow_deathcoil.jpg',
            'Demonology' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_shadow_metamorphosis.jpg',
            'Destruction' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_shadow_rainoffire.jpg',
        ],
        /* Monk */
        10 => [
           'Brewmaster' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_monk_brewmaster_spec.jpg',
           'Windwalker' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_monk_windwalker_spec.jpg',
           'Mistweaver' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_monk_mistweaver_spec.jpg',
        ],
        /* Druid */
        11 => [
            'Balance' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_nature_starfall.jpg',
            'Feral' => 'https://render-eu.worldofwarcraft.com/icons/56/ability_druid_catform.jpg',
            'Guardian' => 'https://render-eu.worldofwarcraft.com/icons/56/ability_racial_bearform.jpg',
            'Restoration' => 'https://render-eu.worldofwarcraft.com/icons/56/spell_nature_healingtouch.jpg',
        ],
        /* Demon Hunter */
        12 => [
            'Havoc' => 'https://render-eu.worldofwarcraft.com/icons/56/ability_demonhunter_specdps.jpg',
            'Vengeance' => 'https://render-eu.worldofwarcraft.com/icons/56/ability_demonhunter_spectank.jpg',
        ],
        '-' => false
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
        if ($this->spec == self::NO_SPEC) return false;
        return $this->specImages[$this->class][$this->spec];
    }

    /**
     * Get race icon
     */
    public function getRaceImg() {
        return 'https://render-eu.worldofwarcraft.com/icons/18/race_' . $this->race . '_0.jpg';
    }

    /**
     * Get human-friendly race name
     */
    public function getRaceName() {
        return $this->raceNames[$this->race];
    }

    /**
     * Can this character tank
     */
    public function canTank() {
        return (bool) in_array($this->class, $this->tanks);
    }

    /**
     * Can this character heal
     */
    public function canHeal() {
        return (bool) in_array($this->class, $this->healers);
    }
}