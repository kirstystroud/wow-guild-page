<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRealmToCharacters extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('characters', function (Blueprint $table) {
            $table->string('server')->after('name')->default(env('WOW_REALM'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn('server');
        });
    }
}
