<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('pets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_ext')->default(0);
            $table->string('name')->default('');
            $table->string('description')->default('');
            $table->string('source')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('pets');
    }
}
