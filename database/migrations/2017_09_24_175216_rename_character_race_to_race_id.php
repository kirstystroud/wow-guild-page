<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCharacterRaceToRaceId extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('characters', function (Blueprint $table) {
            $table->renameColumn('race', 'race_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('characters', function (Blueprint $table) {
            $table->renameColumn('race_id', 'race');
        });
    }
}
