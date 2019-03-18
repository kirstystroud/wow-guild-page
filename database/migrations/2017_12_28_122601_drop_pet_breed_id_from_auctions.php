<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPetBreedIdFromAuctions extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn('pet_breed_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('auctions', function (Blueprint $table) {
            $table->integer('pet_breed_id')->nullable()->index();
        });
    }
}
