<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetTypesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('pet_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_ext')->default(0);
            $table->string('name')->default('');
            $table->integer('strong_against')->default(0);
            $table->integer('weak_against')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('pet_types');
    }
}
