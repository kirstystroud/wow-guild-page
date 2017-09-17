<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->integer('race')->default(0);
            $table->integer('class')->default(0);
            $table->string('spec')->nullable();
            $table->integer('level')->default(0);
            $table->integer('ilvl')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characters');
    }
}
