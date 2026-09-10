<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeWarningEmailsColumnTypeToTextOnHotelSatisfactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_satisfaction', function (Blueprint $table) {
            $table->text('warning_email')->change();
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
            $table->string('warning_email', 255)->change();
        });
    }
}
