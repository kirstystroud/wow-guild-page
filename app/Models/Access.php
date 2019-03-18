<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model {

    public $timestamps = false;

    /**
     * Initialise table in constructor
     *
     * @return {void}
     */
    public function __construct() {
        $this->table = 'access';
    }

    /**
     * Get the current access token from the database
     *
     * @return {string|bool} token if available, false otherwise
     */
    public static function getCurrentToken() {
        $now = Date('Y-m-d H:i:s');
        $token = self::where('expires', '>', $now)->first();
        return $token ? $token->access_token : false;
    }

}
