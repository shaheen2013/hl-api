<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceBlacklistPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permisos_hoteles', function (Blueprint $table) {
            $table->boolean('ip_binding')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('permisos_hoteles', function (Blueprint $table) {
            $table->dropColumn('ip_binding');
        });
    }
}
