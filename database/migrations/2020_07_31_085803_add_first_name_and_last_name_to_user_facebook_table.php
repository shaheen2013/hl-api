<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFirstNameAndLastNameToUserFacebookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_facebook', function (Blueprint $table) {
            $table->string('last_name')->after('nombre')->nullable();
            $table->string('first_name')->after('nombre')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_facebook', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });
    }
}
