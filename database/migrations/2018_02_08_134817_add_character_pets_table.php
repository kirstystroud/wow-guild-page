<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCharacterPetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_pets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('character_id')->default(0)->index();
            $table->integer('pet_id')->default(0)->index();
            $table->string('name')->default('');
            $table->tinyinteger('level')->default(0);
            $table->tinyinteger('quality')->default(0);
            $table->tinyinteger('is_favourite')->default(CharacterPet::NOT_FAVOURITE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_pets');
    }
}
