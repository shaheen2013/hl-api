<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCharLenOnRolesNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_staff_roles', function ($table) {
            $table->string('role_es', 50)->change();
            $table->string('role_en', 50)->change();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_staff_roles', function ($table) {
            $table->string('role_es', 20)->change();
            $table->string('role_en', 20)->change();
        });
    }
}
