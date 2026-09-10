<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditIndexOnUserSatisfactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_satisfaction', function (Blueprint $table) {
            $table->dropIndex(['id_hotel', 'done']);
            $table->dropIndex(['id_cadena', 'done']);

            $table->index(['id_hotel', 'done', 'fecha_update']);
            $table->index(['id_cadena', 'done', 'fecha_update']);
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
            $table->dropIndex(['id_hotel', 'done', 'fecha_update']);
            $table->dropIndex(['id_cadena', 'done', 'fecha_update']);

            $table->index(['id_hotel', 'done']);
            $table->index(['id_cadena', 'done']);
        });
    }
}
