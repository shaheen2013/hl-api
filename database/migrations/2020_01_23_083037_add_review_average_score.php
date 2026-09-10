<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReviewAverageScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_satisfaction', function (Blueprint $table) {
            $table->integer('review_average_score')->default('0');
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
            $table->dropColumn('review_average_score');
        });
    }
}
