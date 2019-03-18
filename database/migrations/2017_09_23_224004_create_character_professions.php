<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharacterProfessions extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('character_professions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('character_id')->default(0);
            $table->integer('profession_id')->default(0);
            $table->integer('skill')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('character_professions');
    }
}
