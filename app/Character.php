<?php

namespace App;

use DB;
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

    public function character_quests() {
        return $this->hasMany(CharacterQuest::class);
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
        return 'https://worldofwarcraft.com/en-gb/character/' . $this->server . '/' . $this->name;
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

    public function getCompletedQuestIdExts() {
        $results = DB::select('
            SELECT id_ext
            FROM character_quests
            LEFT JOIN quests ON character_quests.quest_id = quests.id
            WHERE character_quests.character_id = ?
        ', [$this->id]);

        $results = array_map(function ($value) {
            return (array)$value;
        }, $results);

        $formattedResults = array_column($results, 'id_ext');
        return $formattedResults ? $formattedResults : [];
    }

    public function getKnownRecipesForProfession($professionId) {
        $results = DB::select('
            SELECT recipes.id_ext FROM character_recipes
            LEFT JOIN recipes ON character_recipes.recipe_id = recipes.id
            WHERE character_id = ?
            AND profession_id = ?
        ', [$this->id, $professionId]);

        $results = array_map(function ($value) {
            return (array)$value;
        }, $results);

        $formattedResults = array_column($results, 'id_ext');
        return $formattedResults ? $formattedResults : [];
    }

    public function getEarnedAchievements() {
        $results = DB::select('
            SELECT id_ext
            FROM character_achievements
            LEFT JOIN achievements ON character_achievements.achievement_id = achievements.id
            WHERE character_achievements.character_id = ?
        ', [$this->id]);

        $results = array_map(function ($value) {
            return (array)$value;
        }, $results);

        $formattedResults = array_column($results, 'id_ext');
        return $formattedResults ? $formattedResults : [];
    }
}
