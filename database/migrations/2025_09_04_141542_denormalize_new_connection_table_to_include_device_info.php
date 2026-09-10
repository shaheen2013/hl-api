<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DenormalizeNewConnectionTableToIncludeDeviceInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_connection', function (Blueprint $table) {
            $table->string('device_brand', 50)->nullable();
            $table->index(['brand_id', 'device_brand']);
            $table->index(['brand_id', 'created_at', 'device_brand']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_connection', function (Blueprint $table) {
            $table->dropIndex(['brand_id', 'created_at', 'device_brand']);
            $table->dropIndex(['brand_id', 'device_brand']);
            $table->dropColumn('device_brand');
        });
    }
}
