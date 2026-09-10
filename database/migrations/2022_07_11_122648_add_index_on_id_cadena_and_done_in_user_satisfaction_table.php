<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexOnIdCadenaAndDoneInUserSatisfactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_satisfaction', function (Blueprint $table) {
            $table->index(['id_cadena', 'done']);
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
            $table->dropIndex(['id_cadena', 'done']);
        });
    }
}
