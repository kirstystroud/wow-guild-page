<?php

namespace App\Http\Controllers;

use App\Character;
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

    /**
     * Handles GET requests to /stats/deaths
     */
    public function deaths() {
        $chars = $this->getDeathStats();
        return view('partials.deaths')->with('characters', $chars);
    }

    /**
     * Handles GET requests to /stats/kills
     */
    public function kills() {
        $chars = $this->getKillStats();
        return view('partials.kills')->with('characters', $chars);
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

    protected function getDeathStats() {
        $data = Character::orderBy('deaths', 'DESC')->limit(10)->get();
        return $data;
    }

    protected function getKillStats() {
        $data = Character::orderBy('kdr', 'DESC')->limit(10)->get();
        return $data;
    }
}
