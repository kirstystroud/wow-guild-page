<?php

namespace App\Http\Controllers;

use App\CharacterClass;

use DB;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    /**
     * Handles GET requests to /stats
     */
    public function get() {
        return view('stats');
    }

    /**
     * Handles GET requests to /stats/data
     */
    public function data() {
        return $this->getClassData();
    }

    protected function getClassData() {
        $data = CharacterClass::select(DB::raw(
                    'classes.name,
                    count(*) AS total,
                    min(level) AS min_level,
                    max(level) AS max_level,
                    avg(level) AS avg_level,
                    std(level) AS std_level' 
                ))
            ->join('characters', 'classes.id', 'characters.class_id')
            ->groupBy('classes.name')
            ->get();

        return $data;
    }
}
