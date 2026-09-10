<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomizedSatisfactionTypeOnHotelSatisfactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE hotel_satisfaction MODIFY COLUMN customized_type ENUM('Joined with satisfaction', 'In a later email', 'After PMS checkout date') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE hotel_satisfaction MODIFY COLUMN customized_type ENUM('Joined with satisfaction', 'In a later email') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");
    }
}
