<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToQuests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quests', function (Blueprint $table) {
            $table->index('id_ext');
        });

        Schema::table('quests', function (Blueprint $table) {
            $table->index('category_id');
        });

        Schema::table('quests', function (Blueprint $table) {
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quests', function (Blueprint $table) {
            $table->dropIndex('name');
        });

        Schema::table('quests', function (Blueprint $table) {
            $table->dropIndex('category_id');
        });

        Schema::table('quests', function (Blueprint $table) {
            $table->dropIndex('id_ext');
        });
    }
}
