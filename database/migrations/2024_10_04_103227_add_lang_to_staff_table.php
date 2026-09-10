<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLangToStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // This column is only required for import new users, then the lang will be managed on Cognito
        Schema::table('hotel_staff', function (Blueprint $table) {
            $table->string('lang')->after("email")->default("es");
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
            $table->dropColumn('lang');
        });
    }
}
