<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class AddReviewSendTypeColumnOnHotelReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_review', function (Blueprint $table) {
            $table->increments('id')->first();
            $table->enum('send_type', ['after_wifi', 'after_satisfaction', 'after_check_out']);
        });

        Artisan::call('db:seed', [
            '--class' => 'PopulateReviewSendTypeBasedOnIgnoreRating',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_review', function (Blueprint $table) {
            $table->dropColumn('send_type');
            $table->dropColumn('id');
        });
    }
}
