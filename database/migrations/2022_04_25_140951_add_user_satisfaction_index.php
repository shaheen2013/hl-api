<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserSatisfactionIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_satisfaction', function (Blueprint $table) {
            $table->index(['id_hotel', 'done']);
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
            $table->dropIndex(['id_hotel', 'done']);
        });
    }
}
