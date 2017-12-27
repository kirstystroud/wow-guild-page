<?php

namespace App\Http\Controllers;

use Character;
use CharacterClass;

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
     * Handles GET requests to /stats/data/candlestick
     */
    public function dataCandlestick() {
        return $this->getClassData();
    }

    /**
     * Handles GET requests to /stats/data/pie
     */
    public function dataPie() {
        return $this->getPieData();
    }

    /**
     * Handles GET requests to /stats/deaths
     */
    public function deaths() {
        $data = $this->getDeathStats();
        return view('partials.deaths')->with('data', $data);
    }

    /**
     * Handles GET requests to /stats/kills
     */
    public function kills() {
        $data = $this->getKillStats();
        return view('partials.kills')->with('data', $data);
    }

    /**
     * Get data for candlestick chart of levels by class
     */
    protected function getClassData() {
        $data = [];

        // Get a list of all classes
        $classes = CharacterClass::orderBy('name')->get();
        foreach ($classes as $class) {
            $charLevels = [];
            $chars = $class->characters;
            // Record all character levels for that class
            foreach($chars as $char) {
                $charLevels[] = $char->level;
            }

            // Sort ascending
            sort($charLevels);

            // Lower quartile
            $lowerQ = $this->percentile($charLevels, 0.25);
            $upperQ = $this->percentile($charLevels, 0.75);

            $data[] = [
                'name' => $class->name,
                'total' => count($charLevels),
                'min_level' => $charLevels[0],
                'max_level' => $charLevels[count($charLevels) - 1],
                'avg_level' => (int) round(array_sum($charLevels) / count($charLevels), 0),
                'lower_q' => (int) round($lowerQ),
                'upper_q' => (int) round($upperQ),
                'levels' => $charLevels
            ];
        }

        return $data;
    }

    /**
     * Get data for pie chart of total kills by class
     */
    protected function getPieData() {
        $data = CharacterClass::select(
                    'classes.id_ext',
                    'classes.name',
                    DB::raw('SUM(kills) AS kills')
                )
                ->leftJoin('characters', 'characters.class_id', 'classes.id')
                ->groupBy('classes.id_ext')
                ->groupBy('classes.name')
                ->orderBy('kills', 'DESC')
                ->get();
        return $data;
    }

    /**
     * Calculate percentile from data array
     * @param {array} $data
     * @param {float} $percentile
     * @return {float} $percentile value for data
     */
    protected function percentile($data, $percentile) {
        $pos = (count($data) - 1) * $percentile;
        $lowerIndex = floor($pos);
        $upperIndex = ceil($pos);
        $fraction = $pos - $lowerIndex;
        return $data[$lowerIndex] + ($fraction * ($data[$upperIndex] - $data[$lowerIndex]));
    }

    /**
     * Get data on character deaths for table
     */
    protected function getDeathStats() {
        $mostDeaths = Character::orderBy('deaths', 'DESC')->limit(11)->get();
        $leastDeaths = Character::orderBy('deaths', 'DESC')->offset(Character::count() - 11)->limit(11)->get();
        return [
            'mostDeaths' => $mostDeaths,
            'leastDeaths' => $leastDeaths
        ];
    }

    /**
     * Get data on character kills for table
     */
    protected function getKillStats() {
        $mostKills = Character::orderBy('kdr', 'DESC')->limit(10)->get();
        $leastKills = Character::orderBy('kills', 'DESC')->limit(10)->get();
        return [
            'kdr' => $mostKills,
            'kills' => $leastKills
        ];
    }
}
