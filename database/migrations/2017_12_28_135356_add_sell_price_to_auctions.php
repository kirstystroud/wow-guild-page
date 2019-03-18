<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSellPriceToAuctions extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('auctions', function (Blueprint $table) {
            $table->bigInteger('sell_price')->after('buyout')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn('sell_price');
        });
    }
}
