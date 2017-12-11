<?php

namespace App\Http\Controllers;

use Character;
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
        return 'Data for character ' . $characterId . ' and category ' . $categoryId;
    }

    protected function searchByCharacter($characterId) {
        return 'Data for character ' . $characterId;
    }

    protected function searchByCategory($categoryId) {
        return 'Data for category ' . $categoryId;
    }

    protected function searchAll() {
        return 'All data';
    }
}
