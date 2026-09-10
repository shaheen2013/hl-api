<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultScoreFieldOnHotelSatisfactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_satisfaction', function (Blueprint $table) {
            $table->integer('default_score')->default('6');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_satisfaction', function (Blueprint $table) {
            $table->dropColumn('default_score');
        });
    }
}
