<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model {
    
	public $timestamps = false;

	public function __construct() {
	    $this->table = 'access';
	}

	public static function getCurrentToken() {
		$now = Date('Y-m-d H:i:s');
		$token = self::where('expires', '>', $now)->first();
		return $token ? $token->access_token : false;
	}

}
