<?php

namespace App\Utilities;

use GuzzleHttp;

class BlizzardApi
{

    /** 
     * Make a request to get a list of characters in the guild
     */
    public static function getGuildCharacters() {
        $endpoint = '/wow/guild/' . env('WOW_REALM') . '/' . env('WOW_GUILD');
        $data = [ 'fields' => 'members' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get information on character items
     * @param {string} $charName
     */
    public static function getCharacterItems($charName) {
        $endpoint = '/wow/character/' . env('WOW_REALM') . '/' . $charName;
        $data = [ 'fields' => 'items' ];
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
     * Make a request to get information on character professions
     * @param {string} $charName
     */
    public static function getProfessions($charName) {
        $endpoint = '/wow/character/' . env('WOW_REALM') . '/' . $charName;
        $data = [ 'fields' => 'professions' ];
        return self::makeRequest($endpoint, $data);
    }

    public static function getRecipe($id) {
        $endpoint = '/wow/recipe/' . $id;
        $data = [];
        return self::makeRequest($endpoint, $data);
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
     * Make a request to get character statistics
     */
    public static function getStats($charName) {
        $endpoint = '/wow/character/' . env('WOW_REALM') . '/' . $charName;
        $data = [ 'fields' => 'statistics' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get character titles
     */
    public static function getTitles($charName) {
        $endpoint = '/wow/character/' . env('WOW_REALM') . '/' . $charName;
        $data = [ 'fields' => 'titles' ];
        return self::makeRequest($endpoint, $data);
    }

    public static function getReputation($charName) {
        $endpoint = '/wow/character/' . env('WOW_REALM') . '/' . $charName;
        $data = [ 'fields' => 'reputation'];
        return self::makeRequest($endpoint, $data);
    }

    protected static function makeRequest($endpoint, $data = []) {
        $baseUrl = 'https://' . env('WOW_REGION') . '.api.battle.net/';

        // Required data for all requests
        $data['locale'] = 'en_GB';
        $data['apikey'] = env('WOW_KEY');

        try {
            $client = new GuzzleHttp\Client(['base_uri' => $baseUrl]);
            $req = $client->request('GET', $endpoint, [ 'query' => $data ]);
            return (string) $req->getBody();
        } catch (GuzzleHttp\Exception\ClientException $e) {
            error_log('API request to ' . $endpoint . ' with data ' . json_encode($data) . ' failed with exception ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log('API request to ' . $endpoint . ' with data ' . json_encode($data) . ' failed with exception ' . $e->getMessage());
            return false;
        }
    }
}
