<?php

namespace App\Utilities;

use GuzzleHttp;
use Log;
use App\Models\Access;

class BlizzardApi {


    /**
     * ------------------------------------------------
     * GENERIC DATA CALLS
     * ------------------------------------------------
     */

    /**
     * Make a request to get a list of characters in the guild
     *
     * @return {array}
     */
    public static function getGuildCharacters() {
        $endpoint = '/wow/guild/' . env('WOW_REALM') . '/' . env('WOW_GUILD');
        $data = [ 'fields' => 'members' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to load all dungeon information
     *
     * @return {array}
     */
    public static function getZones() {
        $endpoint = '/wow/zone/';
        return self::makeRequest($endpoint);
    }

    /**
     * Make a request to load classes
     *
     * @return {array}
     */
    public static function getClasses() {
        $endpoint = '/wow/data/character/classes';
        return self::makeRequest($endpoint);
    }

    /**
     * Make a request to load races
     *
     * @return {array}
     */
    public static function getRaces() {
        $endpoint = '/wow/data/character/races';
        return self::makeRequest($endpoint);
    }

    /**
     * Make a request to load guild profile
     *
     * @return {array}
     */
    public static function getGuildProfile() {
        $endpoint = '/wow/guild/' . env('WOW_REALM') . '/' . env('WOW_GUILD');
        return self::makeRequest($endpoint);
    }

    /**
     * Make a request to load pet types
     *
     * @return {array}
     */
    public static function getPetTypes() {
        $endpoint = '/wow/data/pet/types';
        return self::makeRequest($endpoint);
    }

    /**
     * Make a request to get a link to auctions.json
     *
     * @return {array}
     */
    public static function getAuctionDataUrl() {
        $endpoint = '/wow/auction/data/' . env('WOW_REALM');
        $data = self::makeRequest($endpoint);
        return $data['files'][0];
    }


    /**
     * ------------------------------------------------
     * EXTERNAL RESOURCE BY ID CALLS
     * ------------------------------------------------
     */


    /**
     * Make a request to get information on a single recipe
     *
     * @param  {int} $id external recipe id
     * @return {array}
     */
    public static function getRecipe($id) {
        $endpoint = '/wow/recipe/' . $id;
        return self::makeRequest($endpoint);
    }

    /**
     * Load information on a single quest
     *
     * @param  {int} $id external quest id
     * @return {array}
     */
    public static function getQuest($id) {
        $endpoint = '/wow/quest/' . $id;
        return self::makeRequest($endpoint);
    }

    /**
     * Load information on a single achievement
     *
     * @param  {int} $id external achievement id
     * @return {array}
     */
    public static function getAchievement($id) {
        $endpoint = '/wow/achievement/' . $id;
        return self::makeRequest($endpoint);
    }

    /**
     * Load information on a single item
     *
     * @param  {int} $id external item id
     * @return {array}
     */
    public static function getItem($id) {
        $endpoint = '/wow/item/' . $id;
        return self::makeRequest($endpoint);
    }

    /**
     * Load information on a single pet
     *
     * @param  {int} $id external pet species id
     * @return {array}
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
     *
     * @param  {Character} $char
     * @return {array}
     */
    public static function getCharacterItems($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'items' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get information on character professions
     *
     * @param  {Character} $char
     * @return {array}
     */
    public static function getProfessions($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'professions' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get character statistics
     *
     * @param  {Character} $char
     * @return {array}
     */
    public static function getStats($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'statistics' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get character titles
     *
     * @param  {Character} $char
     * @return {array}
     */
    public static function getTitles($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'titles' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get character reputation
     *
     * @param  {Character} $char
     * @return {array}
     */
    public static function getReputation($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'reputation'];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to get character raid runs
     *
     * @param  {Chracter} $char
     * @return {array}
     */
    public static function getRaids($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'progression' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to load character quest progress
     *
     * @param  {Character} $char
     * @return {array}
     */
    public static function getQuests($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'quests' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to load character achievements
     *
     * @param  {Character} $char
     * @return {array}
     */
    public static function getAchievements($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'achievements' ];
        return self::makeRequest($endpoint, $data);
    }

    /**
     * Make a request to load character pets
     *
     * @param  {Character} $char
     * @return {array}
     */
    public static function getPets($char) {
        $endpoint = '/wow/character/' . $char->server . '/' . $char->name;
        $data = [ 'fields' => 'pets' ];
        return self::makeRequest($endpoint, $data);
    }




    /**
     * Make a request
     *
     * @param  {string} $endpoint
     * @param  {array}  $data
     * @return {array} JSON formatted response data
     */
    protected static function makeRequest($endpoint, $data = []) {
        $baseUrl = 'https://' . env('WOW_REGION') . '.api.blizzard.com/';

        // Required data for all requests
        $data['locale'] = 'en_GB';

        $accessToken = self::getAccessToken();
        $data['access_token'] = $accessToken;

        try {
            $client = new GuzzleHttp\Client(['base_uri' => $baseUrl]);
            $req = $client->request('GET', $endpoint, [ 'query' => $data ]);
            $requestBody = (string) $req->getBody();
            return $requestBody ? json_decode($requestBody, true) : false;
        } catch (GuzzleHttp\Exception\ClientException $e) {
            Log::error('API request to ' . $endpoint . ' with data ' . json_encode($data) . ' failed with exception ' . $e->getMessage());
            return false;
        } catch (GuzzleHttp\Exception\ServerException $e) {
            Log::error('API request to ' . $endpoint . ' with data ' . json_encode($data) . ' failed with exception ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            Log::error('API request to ' . $endpoint . ' with data ' . json_encode($data) . ' failed with exception ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get access token required for other requests
     * Pulls it out of database if available
     * If database token has expired, makes request to update database store
     *
     * @return {string} access token to use
     */
    protected static function getAccessToken() {
        $token = Access::getCurrentToken();
        if ($token) {
            // We have a valid one in the database
            return $token;
        }

        // Need to update database
        $uri = 'https://' . env('WOW_REGION') . '.battle.net';
        $clientAccess = env('WOW_CLIENT_ACCESS');
        $clientSecret = env('WOW_CLIENT_SECRET');

        $response = false;
        try {
            $client = new GuzzleHttp\Client([
                'base_uri' => $uri,
                'auth' => [$clientAccess, $clientSecret],
            ]);
            $req = $client->request('GET', '/oauth/token', ['query' => ['grant_type' => 'client_credentials']]);
            $response = (string) $req->getBody();
        } catch (Exception $e) {
            Log::error('API request to ' . $endpoint . ' with data ' . json_encode($data) . ' failed with exception ' . $e->getMessage());
            throw $e;
        }

        // Parse response and update database
        $responseArray = json_decode($response, true);
        $expiry = $responseArray['expires_in'];

        // Calculate absolute expiry time
        $expiryTimestamp = Date('Y-m-d H:i:s', time() + $expiry);

        $access = new Access;
        $access->access_token = $responseArray['access_token'];
        $access->expires = $expiryTimestamp;
        $access->save();
        return $responseArray['access_token'];
    }
}
