<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToReputations extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('reputations', function (Blueprint $table) {
            $table->index('character_id');
        });

        Schema::table('reputations', function (Blueprint $table) {
            $table->index('faction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('reputations', function (Blueprint $table) {
            $table->dropIndex('character_id');
        });

        Schema::table('reputations', function (Blueprint $table) {
            $table->dropIndex('faction_id');
        });
    }
}
