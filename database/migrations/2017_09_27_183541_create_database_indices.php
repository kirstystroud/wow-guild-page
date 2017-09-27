<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatabaseIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->integer('class_id')->index()->change();
        });

        Schema::table('character_professions', function (Blueprint $table) {
            $table->integer('character_id')->index()->change();
            $table->integer('profession_id')->index()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropIndex('characters_class_id_index');
        });
        Schema::table('character_professions', function (Blueprint $table) {
            $table->dropIndex('character_professions_character_id_index');
            $table->dropIndex('character_professions_profession_id_index');
        });
    }
}
