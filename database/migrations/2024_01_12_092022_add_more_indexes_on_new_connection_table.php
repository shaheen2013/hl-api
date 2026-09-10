<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreIndexesOnNewConnectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_connection', function (Blueprint $table) {
            $table->index(['brand_id', 'device_id', 'created_at']);
            $table->index(['brand_id', 'access_type_id', 'created_at']);
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
            $table->dropIndex(['brand_id', 'device_id', 'created_at']);
            $table->dropIndex(['brand_id', 'access_type_id', 'created_at']);
        });
    }
}
