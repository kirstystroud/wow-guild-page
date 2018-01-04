<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateLastSeenToAuctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            // sqlite does not like non-static defaults
            if (env('DB_CONNECTION') == 'sqlite') {
                $table->timestamp('date_last_seen')->default(0);
            } else {
                $table->dateTime('date_last_seen')->default(DB::raw('CURRENT_TIMESTAMP'));
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn('date_last_seen');
        });
    }
}
