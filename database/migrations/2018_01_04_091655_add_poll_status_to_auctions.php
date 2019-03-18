<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPollStatusToAuctions extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('auctions', function (Blueprint $table) {
            $table->tinyInteger('poll_status')->default(Auction::POLL_STATUS_PENDING)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn('poll_status');
        });
    }
}
