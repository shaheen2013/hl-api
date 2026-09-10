<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexOnUserSatisfactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_satisfaction', function (Blueprint $table) {
            $table->index(['id_hotel', 'done', 'puntuacion']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_satisfaction', function (Blueprint $table) {
            $table->dropIndex(['id_hotel', 'done', 'puntuacion']);
        });
    }
}
