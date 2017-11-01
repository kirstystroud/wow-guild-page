<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharacterDungeonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_dungeons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('character_id')->default(0)->index();
            $table->integer('dungeon_id')->default(0)->index();
            $table->integer('lfr')->default(0);
            $table->integer('normal')->default(0);
            $table->integer('heroic')->default(0);
            $table->integer('mythic')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_dungeons');
    }
}
