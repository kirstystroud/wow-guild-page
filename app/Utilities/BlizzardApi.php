<?php

namespace App\Utilities;

use GuzzleHttp;

class BlizzardApi {


    /**
     * ------------------------------------------------
     * GENERIC DATA CALLS
     * ------------------------------------------------
     */

    /** 
     * Make a request to get a list of characters in the guild
     */
    public static function getGuildCharacters() {
        $endpoint = '/wow/guild/' . env('WOW_REALM') . '/' . env('WOW_GUILD');
        $data = [ 'fields' => 'members' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to load all dungeon information
     */
    public static function getZones() {
        $endpoint = '/wow/zone/';
        return self::makeRequest($endpoint);
    }

    /**
     * Make a request to load classes
     */
    public static function getClasses() {
        $endpoint = '/wow/data/character/classes';
        return self::makeRequest($endpoint);
    }

    /**
     * Make a request to load races
     */
    public static function getRaces() {
        $endpoint = '/wow/data/character/races';
        return self::makeRequest($endpoint);
    }

    /**
     * Make a request to load guild profile
     */
    public static function getGuildProfile() {
        $endpoint = '/wow/guild/' . env('WOW_REALM') . '/' . env('WOW_GUILD');
        return self::makeRequest($endpoint);
    }

    /**
     * Make a request to load pet types
     */
    public static function getPetTypes() {
        $endpoint = '/wow/data/pet/types';
        return self::makeRequest($endpoint);
    }


    /**
     * ------------------------------------------------
     * EXTERNAL RESOURCE BY ID CALLS
     * ------------------------------------------------
     */


    /**
     * Make a request to get information on a single recipe
     * @param {int} $id
     */
    public static function getRecipe($id) {
        $endpoint = '/wow/recipe/' . $id;
        return self::makeRequest($endpoint);
    }

    /**
     * Load information on a single quest
     * @param {int} $id
     */
    public static function getQuest($id) {
        $endpoint = '/wow/quest/' . $id;
        return self::makeRequest($endpoint);
    }

    /**
     * Load information on a single achievement
     * @param {int} $id
     */
    public static function getAchievement($id) {
        $endpoint = '/wow/achievement/' . $id;
        return self::makeRequest($endpoint);
    }

    /**
     * Load information on a single item
     */
    public static function getItem($id) {
        $endpoint = '/wow/item/' . $id;
        return self::makeRequest($endpoint);
    }

    /**
     * Load information on a single pet
     */
    public static function getPetSpecies($id) {
        $endpoint = '/wow/pet/species/' . $id;
        return self::makeRequest($endpoint);
    }



    /**
     * ------------------------------------------------
     * CHARACTER DATA CALLS
     * ------------------------------------------------
     */

    /**
     * Make a request to get information on character items
     * @param {Character} $char
     */
    public static function getCharacterItems($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'items' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get information on character professions
     * @param {Character} $char
     */
    public static function getProfessions($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'professions' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get character statistics
     * @param {Character} $char
     */
    public static function getStats($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'statistics' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get character titles
     * @param {Character} $char
     */
    public static function getTitles($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'titles' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get character reputation
     * @param {Character} $char
     */
    public static function getReputation($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'reputation'];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get character raid runs
     * @param {Chracter} $char
     */
    public static function getRaids($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'progression' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to load character quest progress
     * @param {Character} $char
     */
    public static function getQuests($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'quests' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to load character achievements
     * @param {Character} $char
     */
    public static function getAchievements($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'achievements' ];
        return self::makeRequest($endpoint, $data);
    }





    /**
     * Make a request
     * @param {string} $endpoint
     * @param {array} $data
     * @return {array} JSON formatted response data
     */
    protected static function makeRequest($endpoint, $data = []) {
        $baseUrl = 'https://' . env('WOW_REGION') . '.api.battle.net/';

        // Required data for all requests
        $data['locale'] = 'en_GB';
        $data['apikey'] = env('WOW_KEY');

        try {
            $client = new GuzzleHttp\Client(['base_uri' => $baseUrl]);
            $req = $client->request('GET', $endpoint, [ 'query' => $data ]);
            $requestBody = (string) $req->getBody();
            return $requestBody ? json_decode($requestBody, true) : false;
        } catch (GuzzleHttp\Exception\ClientException $e) {
            // error_log('API request to ' . $endpoint . ' with data ' . json_encode($data) . ' failed with exception ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            // error_log('API request to ' . $endpoint . ' with data ' . json_encode($data) . ' failed with exception ' . $e->getMessage());
            return false;
        }
    }
}
