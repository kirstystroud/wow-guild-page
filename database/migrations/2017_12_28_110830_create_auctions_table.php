<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('auctions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_ext')->default(0)->index();
            $table->integer('item_id')->default(0)->index();
            $table->integer('bid')->default(0);
            $table->integer('buyout')->default(0);
            $table->integer('status')->default(Auction::STATUS_ACTIVE);
            $table->integer('pet_id')->nullable()->index();
            $table->integer('pet_breed_id')->nullable()->index();
            $table->integer('pet_level')->nullable();
            $table->integer('pet_quality')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('auctions');
    }
}
