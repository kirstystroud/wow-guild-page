<?php

namespace App\Models;

use CharacterFilter;

use DB;
use Illuminate\Database\Eloquent\Model;

class Character extends Model {

    // Character spec is unknown
    const NO_SPEC = '-';

    public $timestamps = false;

    // Classes which can tank
    protected $tanks = [ 1, 2, 6, 10, 11, 12 ];

    // Classes which can heal
    protected $healers = [ 2, 5, 7, 10, 11 ];



    // Public relations

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


    // Enable filtering

    public function scopeFilter($builder, CharacterFilter $filters) {
        return $filters->apply($builder);
    }


    // Public helper functions

    /**
     * Get class icon
     * @return {string}
     */
    public function getClassImg() {
        return 'https://render-eu.worldofwarcraft.com/icons/18/class_' . $this->character_class->id_ext . '.jpg';
    }

    /**
     * Get race icon
     * @return {string}
     */
    public function getRaceImg() {
        return 'https://render-eu.worldofwarcraft.com/icons/18/race_' . $this->race->id_ext . '_0.jpg';
    }

    /**
     * Get link to external character page
     * @return {string}
     */
    public function getLinkAddr() {
        return 'https://worldofwarcraft.com/en-gb/character/' . $this->server . '/' . $this->name;
    }

    /**
     * Can this character tank
     * @return {bool}
     */
    public function canTank() {
        return (bool) in_array($this->character_class->id_ext, $this->tanks);
    }

    /**
     * Can this character heal
     * @return {bool}
     */
    public function canHeal() {
        return (bool) in_array($this->character_class->id_ext, $this->healers);
    }

    /**
     * Update character last activity to the current time
     */
    public function updateLastActivity() {
        $this->last_activity = Date('Y-m-d H:i:s', time());
    }

    /**
     * Get last activity in human-readable format for front-end
     * @return {string}
     */
    public function getLastActivity() {
        // No activity recorded
        if (!isset($this->last_activity)) {
            return '-';
        }

        $currentTime = time();
        $lastActivity = strtotime($this->last_activity);
        $difference = $currentTime - $lastActivity;

        $units = 0;
        $interval = '';

        switch(true) {
            case ($difference < 60) :
                // Less that one minute ago
                $units = $difference;
                $interval = 'second';
            break;

            case ($difference < 3600) :
                // Less than one hour ago
                $units = floor($difference / 60);
                $interval = 'minute';
            break;

            case ($difference < 86400) :
                // Less than one day ago
                $units = floor($difference / 3600);
                $interval = 'hour';
            break;

            default:
                // more than 1 day ago
                $units = floor($difference / 86400);
                $interval = 'day';
        }

        if ($units != 1) {
            $interval .= 's';
        }

        return $units . ' ' . $interval . ' ago';
    }

    /**
     * Construct title for this character
     * @return {string}
     */
    public function getTitle() {
        if (!$this->title_id) {
            return $this->name;
        }
        return str_replace('%s', $this->name, $this->title->name);
    }

    /**
     * Get a list of quest id_exts completed by this character
     * @return {array}
     */
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

    /**
     * Get a list of recipe id_exts known by this character
     * @return {array}
     */
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

    /**
     * Get a list of achievement id_exts completed by this character
     * @return {array}
     */
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

    /**
     * Get a list of categories and how many quests completed there for a character
     */
    public function getCategoriesByQuestsCompleted() {
        return CharacterQuest::select('categories.name', 'categories.id AS category_id', \DB::raw('COUNT(DISTINCT quests.name) as count'))
                ->join('quests', 'quest_id', 'quests.id')
                ->join('categories', 'category_id', 'categories.id')
                ->where('character_quests.character_id', $this->id)
                ->groupBy('categories.name')
                ->groupBy('categories.id')
                ->orderBy('categories.name')
                ->get();
    }

    /**
     * Count the quests completed in a category
     * @param {int} $categoryId
     * @return (int) $count
     */
    public function countQuestsCompletedInCategory($categoryId) {
        $count = CharacterQuest::select(\DB::raw('COUNT(DISTINCT quests.name) as count'))
            ->join('quests', 'quest_id', 'quests.id')
            ->where('character_quests.character_id', $this->id)
            ->where('quests.category_id', $categoryId)
            ->first();
        return $count->count;
    }

    /**
     * Does this character own a pet
     * @param {int} $petId
     * @return {bool}
     */
    public function doesOwnPet($petId) {
        $count = CharacterPet::where('character_id', $this->id)
                ->where('pet_id', $petId)
                ->count();
        return (bool) $count;
    }
}
