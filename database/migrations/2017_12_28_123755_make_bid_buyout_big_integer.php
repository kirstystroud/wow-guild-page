<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeBidBuyoutBigInteger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->bigInteger('bid')->default(0)->change();
        });

        Schema::table('auctions', function (Blueprint $table) {
            $table->bigInteger('buyout')->default(0)->change();
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
            $table->integer('bid')->default(0)->change();
        });

        Schema::table('auctions', function (Blueprint $table) {
            $table->integer('buyout')->default(0)->change();
        });
    }
}
