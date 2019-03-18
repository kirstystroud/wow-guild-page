<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReputationTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('reputations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('character_id')->default(0);
            $table->integer('faction_id')->default(0);
            $table->integer('standing')->default(0);
            $table->integer('current')->default(0);
            $table->integer('max')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('reputations');
    }
}
