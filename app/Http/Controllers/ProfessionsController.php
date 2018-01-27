<?php

namespace App\Http\Controllers;

use Profession;
use Recipe;

use Illuminate\Http\Request;

class ProfessionsController extends Controller {

    const COUNT_LIMIT = 50;

    /**
     * Handles GET requests to /professions
     * Returns view with details on professions
     */
    public function get() {
        $professions = Profession::orderBy('name', 'asc')->get();
        return view('professions.index')->with('professions', $professions);
    }

    /**
     * Handles GET requests to /professions/search
     * Return view containing search results
     */
    public function search(Request $request) {
        $name = $request->name;
        $professionId = $request->profession;

        $query = Recipe::where('name', 'LIKE', '%' . $name . '%');
        if ($professionId) {
            $query = $query->where('profession_id', $professionId);
        }

        $count = $query->count();
        if ($count > self::COUNT_LIMIT) {
            return '<br>' . 'Too many results, please try refining your search';
        } else if ($count == 0) {
            return '<br>' . 'No results found';
        }

        $recipes = $query->orderBy('profession_id')->orderBy('name')->get();

        return view('professions.partials.recipe-search-results')->with('recipes', $recipes);
    }
}
