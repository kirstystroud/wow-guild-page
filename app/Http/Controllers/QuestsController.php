<?php

namespace App\Http\Controllers;

use Character;
use CharacterQuest;
use Category;

use Illuminate\Http\Request;

class QuestsController extends Controller {

    /**
     * Handles GET requests to /quests
     * Returns view with list of characters/categories but empty panel
     */
    public function get() {
        $characters = Character::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('quests')->with('characters', $characters)->with('categories', $categories);
    }

    /**
     * Handles GET requests to /quests/search
     * Performs search based on user-supplied criteria and returns results as view
     */
    public function search(Request $request) {
        $characterProvided = (bool) ($request->character > 0);
        $categoryProvided = (bool) ($request->category > 0);

        switch (true) {
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
     * Search for a specific character and a specific category
     * Returns view with list of all quests completed by that character in that category
     * @param {int} $characterId
     * @param {int} $categoryId
     */
    protected function searchByCharacterAndCategory($characterId, $categoryId) {
        $characterQuests = CharacterQuest::select('quests.name')
                            ->join('quests', 'quest_id', 'quests.id')
                            ->where('character_id', $characterId)
                            ->where('category_id', $categoryId)
                            ->groupBy('quests.name')
                            ->orderBy('quests.name', 'asc')
                            ->get();
        return view('partials.quests.character-categories')->with('quests', $characterQuests);
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
        return view('partials.quests.characters')->with('categories', $characterQuests)->with('character_id', $characterId);
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
        return view('partials.quests.categories')->with('characters', $characterQuests)->with('category_id', $categoryId);
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
        return view('partials.quests.all')->with('characters', $characters);
    }
}
