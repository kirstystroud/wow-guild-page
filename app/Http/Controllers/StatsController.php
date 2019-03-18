<?php

namespace App\Http\Controllers;

use Character;
use CharacterClass;
use CharacterQuest;

use DB;
use Illuminate\Http\Request;

class StatsController extends Controller {

    /**
     * Handles GET requests to /stats
     *
     * @return {view} page framework
     */
    public function get() {
        return view('stats.index');
    }

    /**
     * Handles GET requests to /stats/data/candlestick
     *
     * @return {array} formatted data for candlestick chart
     */
    public function dataCandlestick() {
        return $this->getClassData();
    }

    /**
     * Handles GET requests to /stats/data/pie
     *
     * @return {array} formatted data for pie chart
     */
    public function dataPie() {
        return $this->getPieData();
    }

    /**
     * Handles GET requests to /stats/deaths
     *
     * @return {view} formatted deaths table
     */
    public function deaths() {
        $data = $this->getDeathStats();
        return view('stats.partials.deaths')->with('data', $data);
    }

    /**
     * Handles GET requests to /stats/kills
     *
     * @return {view} formatted kills table
     */
    public function kills() {
        $data = $this->getKillStats();
        return view('stats.partials.kills')->with('data', $data);
    }

    /**
     * Handles GET requests to /stats/pvpkills
     *
     * @return {view} formatted pvp kills table
     */
    public function pvpKills() {
        $data = $this->getPvpKillStats();
        return view('stats.partials.pvp-kills')->with('data', $data);
    }

    /**
     * Handles GET requests to /stats/dungeons
     *
     * @return {view} formatted dungeons table
     */
    public function dungeons() {
        $data = $this->getDungeonStats();
        return view('stats.partials.dungeons')->with('data', $data);
    }

    /**
     * Handles GET requests to /stats/raids
     *
     * @return {view} formatted raids table
     */
    public function raids() {
        $data = $this->getRaidStats();
        return view('stats.partials.raids')->with('data', $data);
    }

    /**
     * Handles GET requests to /stats/quests
     *
     * @return {array} formatted data for quests pie chart
     */
    public function dataPieQuests() {
        return $this->getQuestsData();
    }

    /**
     * Get data for candlestick chart of levels by class
     *
     * @return {array} formatted class breakdown for candlestick chart
     */
    protected function getClassData() {
        $data = [];

        // Get a list of all classes
        $classes = CharacterClass::orderBy('name')->get();
        foreach ($classes as $class) {
            $charLevels = [];
            $chars = $class->characters;
            // Record all character levels for that class
            foreach ($chars as $char) {
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
     *
     * @return {array}
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
     * Get data for pie chart of quests by class
     *
     * @return {array} formatted sorted data
     */
    protected function getQuestsData() {
        $characters = CharacterQuest::select('character_id', 'class_id', 'classes.name AS class_name', \DB::raw('COUNT(DISTINCT quests.name) as count'))
                            ->join('quests', 'quest_id', 'quests.id')
                            ->join('characters', 'character_id', 'characters.id')
                            ->join('classes', 'class_id', 'classes.id')
                            ->groupBy('character_id')
                            ->orderBy('count', 'desc')
                            ->get();

        // Combine for each class
        $summary = [];
        foreach ($characters as $char) {
            $classId = $char['class_id'];
            if (isset($summary[$classId])) {
                $summary[$classId]['quests'] += $char['count'];
            } else {
                $summary[$classId] = [
                    'id_ext' => $classId,
                    'name' => $char->class_name,
                    'quests' => $char['count']
                ];
            }
        }

        // Prepare for sorting
        $values = array_values($summary);
        // Sort by quests
        usort($values, function($a, $b) {
            return $b['quests'] - $a['quests'];
        });

        return $values;
    }

    /**
     * Calculate percentile from data array
     *
     * @param  {array} $data
     * @param  {float} $percentile
     * @return {float} $percentile value for data
     */
    protected function percentile($data, $percentile) {
        // Position in ordered list
        $pos = (count($data) - 1) * $percentile;
        // Lower index in list
        $lowerIndex = floor($pos);
        // Upper index in list
        $upperIndex = ceil($pos);
        // How far between indicies to take
        $fraction = $pos - $lowerIndex;
        return $data[$lowerIndex] + ($fraction * ($data[$upperIndex] - $data[$lowerIndex]));
    }

    /**
     * Get data on character deaths for table
     *
     * @return {array}
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
     *
     * @return {array}
     */
    protected function getKillStats() {
        $mostKills = Character::orderBy('kdr', 'DESC')->limit(10)->get();
        $leastKills = Character::orderBy('kills', 'DESC')->limit(10)->get();
        return [
            'kdr' => $mostKills,
            'kills' => $leastKills
        ];
    }

    /**
     * Get data on character pvp kills for table
     *
     * @return {array}
     */
    protected function getPvpKillStats() {
        $pvpKills = Character::orderBy('pvp_kills', 'DESC')->orderBy('level', 'DESC')->orderBy('name', 'ASC')->limit(10)->get();
        return $pvpKills;
    }


    /**
     * Get data on character dungeons entered for table
     *
     * @return {array}
     */
    protected function getDungeonStats() {
        $mostDungeons = Character::orderBy('dungeons_entered', 'DESC')->limit(10)->get();
        return $mostDungeons;
    }

    /**
     * Get data on character raids entered for table
     *
     * @return {array}
     */
    protected function getRaidStats() {
        $mostRaids = Character::orderBy('raids_entered', 'DESC')->limit(10)->get();
        return $mostRaids;
    }
}
