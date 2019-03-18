<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKillsDeathsToCharacters extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('characters', function (Blueprint $table) {
            $table->integer('deaths')->default(0);
        });

        Schema::table('characters', function (Blueprint $table) {
            $table->integer('kills')->default(0);
        });

        Schema::table('characters', function (Blueprint $table) {
            $table->integer('kdr')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn('deaths');
        });

        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn('kills');
        });

        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn('kdr');
        });
    }
}
