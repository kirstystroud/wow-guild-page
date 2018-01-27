<?php

namespace App\Http\Controllers;

use Character;
use CharacterQuest;
use Category;
use Quest;

use Illuminate\Http\Request;

class QuestsController extends Controller {

    /**
     * Handles GET requests to /quests
     * Returns view with list of characters/categories but empty panel
     */
    public function get() {
        $characters = Character::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('quests.index')->with('characters', $characters)->with('categories', $categories);
    }

    /**
     * Handles GET requests to /quests/search
     * Performs search based on user-supplied criteria and returns results as view
     */
    public function search(Request $request) {
        $characterProvided = (bool) ($request->character > 0);
        $categoryProvided = (bool) ($request->category > 0);
        $compareProvided = (bool) ($request->compare > 0);

        switch (true) {
            case ($characterProvided && $categoryProvided && $compareProvided) :
                return $this->searchByCharacterAndCategoryWithCompare($request->character, $request->category, $request->compare);
                break;
            case ($characterProvided && $categoryProvided) :
                return $this->searchByCharacterAndCategory($request->character, $request->category);
                break;
            case $characterProvided :
                return $this->searchByCharacter($request->character);
                break;
            case $categoryProvided :
                return $this->searchByCategory($request->category);
                break;
            default :
                return $this->searchAll();
        }
    }

    /**
     * Search for a specific character and category whilsts comparing against another character
     * @param {int} $characterId
     * @param {int} $categoryId
     * @param {int} $compareId
     */
    protected function searchByCharacterAndCategoryWithCompare($characterId, $categoryId, $compareId) {
        $otherCharacters = $this->getOtherCharacters($characterId, $categoryId);

        // Load characters for view
        $character = Character::find($characterId);
        $compareCharacter = Character::find($compareId);

        // List of all quests in this category
        $quests = Quest::select('name')->where('category_id', $categoryId)->groupBy('name')->get();

        // List of all quests completed by each character
        $firstCharacterQuests = $this->getQuestListByCharacterCategory($characterId, $categoryId);
        $compareCharacterQuests = $this->getQuestListByCharacterCategory($compareId, $categoryId);

        // Construct empty summary
        $summary = array();
        foreach($quests as $quest) {
            $summary[$quest->name] = [
                'character' => false,
                'compare' => false
            ];
        }

        // Add in quests for first character
        foreach($firstCharacterQuests as $quest) {
            $summary[$quest->name]['character'] = true;
        }

        // Add in quests for second character
        foreach($compareCharacterQuests as $quest) {
            $summary[$quest->name]['compare'] = true;
        }

        return view('quests.partials.character-categories-compare')
                ->with('character', $character)
                ->with('compareCharacter', $compareCharacter)
                ->with('quests', $summary)
                ->with('otherCharacters', $otherCharacters)
                ->with('compare', $compareId);
    }

    /**
     * Search for a specific character and a specific category
     * Returns view with list of all quests completed by that character in that category
     * @param {int} $characterId
     * @param {int} $categoryId
     */
    protected function searchByCharacterAndCategory($characterId, $categoryId) {

        $characterQuests = $this->getQuestListByCharacterCategory($characterId, $categoryId);
        $otherCharacters = $this->getOtherCharacters($characterId, $categoryId);

        return view('quests.partials.character-categories')->with('quests', $characterQuests)->with('otherCharacters', $otherCharacters);
    }

    /**
     * Get a list of characters who have completed quests in a zone with an exclusion
     * @param {int} $characterId
     * @param {int} $categoryId
     * @return {Array}
     */
    protected function getOtherCharacters($characterId, $categoryId) {
        $otherCharacters = Character::select('characters.id', 'characters.name')
                            ->join('character_quests', 'characters.id', 'character_id')
                            ->join('quests', 'quest_id', 'quests.id')
                            ->where('category_id', $categoryId)
                            ->where('characters.id', '!=', $characterId)
                            ->whereNotNull('character_quests.id')
                            ->groupBy('characters.id')
                            ->orderBy('characters.name')
                            ->get();
        return $otherCharacters;
    }

    /**
     * Get a lit of characters completed by a specific character in a specific category
     * @param {int} $characterId
     * @param {int} $categoryId
     * @return {Array}
     */
    protected function getQuestListByCharacterCategory($characterId, $categoryId) {
        $characterQuests = CharacterQuest::select('quests.name')
                            ->join('quests', 'quest_id', 'quests.id')
                            ->where('character_id', $characterId)
                            ->where('category_id', $categoryId)
                            ->groupBy('quests.name')
                            ->orderBy('quests.name', 'asc')
                            ->get();
        return $characterQuests;
    }

    /**
     * Search for a specific character
     * Returns view with summary of how many quests that character has completed by category
     * @param {int} $characterId
     */
    protected function searchByCharacter($characterId) {
        $characterQuests = CharacterQuest::select('categories.name', 'categories.id AS category_id', \DB::raw('COUNT(DISTINCT quests.name) as count'))
                            ->join('quests', 'quest_id', 'quests.id')
                            ->join('categories', 'category_id', 'categories.id')
                            ->where('character_quests.character_id', $characterId)
                            ->groupBy('categories.name')
                            ->groupBy('categories.id')
                            ->orderBy('categories.name')
                            ->get();
        return view('quests.partials.characters')->with('categories', $characterQuests)->with('character_id', $characterId);
    }

    /**
     * Search for a specific category
     * Returns view with summary of how many quests different characters have completed in that category
     * @param {int} $categoryId
     */
    protected function searchByCategory($categoryId) {
        $characterQuests = CharacterQuest::select('characters.name', 'characters.id AS character_id', \DB::raw('COUNT(DISTINCT quests.name) as count'))
                            ->join('quests', 'quest_id', 'quests.id')
                            ->join('characters', 'character_id', 'characters.id')
                            ->where('quests.category_id', $categoryId)
                            ->groupBy('characters.name')
                            ->groupBy('characters.id')
                            ->orderBy('characters.name')
                            ->get();
        return view('quests.partials.categories')->with('characters', $characterQuests)->with('category_id', $categoryId);
    }

    /**
     * Search over all characters and categories
     * Returns view with summary of total quests completed by each character
     */
    protected function searchAll() {
        $characters = CharacterQuest::select('character_id', \DB::raw('COUNT(DISTINCT quests.name) as count'))
                            ->join('quests', 'quest_id', 'quests.id')
                            ->groupBy('character_id')
                            ->orderBy('count', 'desc')
                            ->get();
        return view('quests.partials.all')->with('characters', $characters);
    }
}
