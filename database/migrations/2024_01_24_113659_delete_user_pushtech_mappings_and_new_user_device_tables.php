<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteUserPushtechMappingsAndNewUserDeviceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists("pushtech_users_mappings");
        Schema::dropIfExists("new_user_device");

        Schema::table('new_device', function (Blueprint $table) {
            $table->dropIndex(["mac_address"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_device', function (Blueprint $table) {
            $table->index(["mac_address"]);
        });

        Schema::create('new_user_device', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id');
            $table->integer('device_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
            $table->foreign('device_id')->references('id')->on('new_device');		
            $table->foreign('user_id')->references('id')->on('users');
        });
      
        Schema::create('pushtech_users_mappings', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('id_user');
            $table->string('id_pushtech')->default('');
            $table->integer('id_hotel');
        });
    }
}
