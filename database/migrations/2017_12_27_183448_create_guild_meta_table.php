<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuildMetaTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('guild_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('key')->default(Meta::KEY_UNKNOWN);
            $table->string('value')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('guild_meta');
    }
}
