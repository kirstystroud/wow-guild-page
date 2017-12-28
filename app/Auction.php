<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model {

    const STATUS_ACTIVE = 0;
    const STATUS_SELLING = 1;
    const STATUS_SOLD = 2;
    const STATUS_ENDED = -1;

}
