<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model {

    public $timestamps = false;

    // Meta keys
    const KEY_UNKNOWN = 0;
    const KEY_TABARD = 10;
    CONST KEY_TABARD_HOOKS = 11;

    // Keys for tabard json strong
    const TABARD_ICON = 'icon';
    const TABARD_ICON_COLOR = 'icon_color';
    const TABARD_ICON_COLOR_DATA = 'icon_color_data';
    const TABARD_BORDER = 'border';
    const TABARD_BORDER_COLOR = 'border_color';
    const TABARD_BORDER_COLOR_DATA = 'border_color_data';
    const TABARD_BACKGROUND_COLOR = 'background_color';
    const TABARD_BACKGROUND_COLOR_DATA = 'background_color_data';


    public function __construct() {
        $this->table = 'guild_meta';
    }


    // Public static helper functions

    /**
     * Set meta value
     * @param {int} $key
     * @param {string} $value
     */
    public static function addMeta($key, $value) {

        // Check for existing meta entry
        $meta = self::where('key', $key)->first();
        if (!$meta) {
            $meta = new static();
            $meta->key = $key;
        }

        // Update value
        $meta->value = $value;
        $meta->save();
    }

    /**
     * Retrieve a meta value
     * @param {int} $key
     * @return {string} value
     */
    public static function getMeta($key) {
        $result = self::where('key', $key)->first();
        return $result ? $result->value : false;
    }
}
