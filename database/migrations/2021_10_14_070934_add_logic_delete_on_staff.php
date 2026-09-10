<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogicDeleteOnStaff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_staff', function (Blueprint $table) {
            $table->boolean('deleted')->default(false)->after("activo");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_staff', function (Blueprint $table) {
            $table->dropColumn('deleted');
        });
    }
}
