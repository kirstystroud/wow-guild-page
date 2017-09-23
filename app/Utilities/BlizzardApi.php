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

        $data = [
            'fields' => 'members',
            'locale' => 'en_GB',
            'apikey' => env('WOW_KEY')
        ];

        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get information on character items
     * @param {string} $charName
     */
    public static function getCharacterItems($charName) {
        $endpoint = '/wow/character/' . env('WOW_REALM') . '/' . $charName;

        $data = [
            'fields' => 'items',
            'locale' => 'en_GB',
            'apikey' => env('WOW_KEY')
        ];

        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to load all dungeon information
     */
    public static function getZones() {
        $endpoint = '/wow/zone/';

        $data = [
            'locale' => 'en_GB',
            'apikey' => env('WOW_KEY')
        ];

        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get information on character professions
     * @param {string} $charName
     */
    public static function getProfessions($charName) {
        $endpoint = '/wow/character/' . env('WOW_REALM') . '/' . $charName;

        $data = [
            'fields' => 'professions',
            'locale' => 'en_GB',
            'apikey' => env('WOW_KEY')
        ];

        return self::makeRequest($endpoint, $data);
    }

    protected static function makeRequest($endpoint, $data) {
        $baseUrl = 'https://' . env('WOW_REGION') . '.api.battle.net/';

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
