<?php

namespace App\Http\Controllers;

use Character;
use CharacterQuest;
use Category;

use Illuminate\Http\Request;

class QuestsController extends Controller
{
    public function get() {
        $characters = Character::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('quests')->with('characters', $characters)->with('categories', $categories);
    }

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

    protected function searchByCharacter($characterId) {
        $characterQuests = CharacterQuest::select('categories.name', 'categories.id AS category_id', \DB::raw('COUNT(*) as count'))
                            ->join('quests', 'quest_id', 'quests.id')
                            ->join('categories', 'category_id', 'categories.id')
                            ->where('character_quests.character_id', $characterId)
                            ->groupBy('categories.name')
                            ->groupBy('categories.id')
                            ->orderBy('categories.name')
                            ->get();
        return view('partials.quests.characters')->with('categories', $characterQuests)->with('character_id', $characterId);
    }

    protected function searchByCategory($categoryId) {
        $characterQuests = CharacterQuest::select('characters.name', 'characters.id AS character_id', \DB::raw('COUNT(*) as count'))
                            ->join('quests', 'quest_id', 'quests.id')
                            ->join('characters', 'character_id', 'characters.id')
                            ->where('quests.category_id', $categoryId)
                            ->groupBy('characters.name')
                            ->groupBy('characters.id')
                            ->orderBy('characters.name')
                            ->get();
        return view('partials.quests.categories')->with('characters', $characterQuests)->with('category_id', $categoryId);
    }

    protected function searchAll() {
        $characters = CharacterQuest::select('character_id', \DB::raw('COUNT(*) as count'))
                            ->groupBy('character_id')
                            ->orderBy('count', 'desc')
                            ->get();
        return view('partials.quests.all')->with('characters', $characters);
    }
}
