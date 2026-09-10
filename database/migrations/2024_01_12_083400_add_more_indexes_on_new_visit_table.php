<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreIndexesOnNewVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_visit', function (Blueprint $table) {
            $table->dropIndex(['brand_id', 'check_out']);
            $table->index(['brand_id', 'check_out', 'check_in', 'is_client']);
            $table->index(['brand_id', 'is_client']);
            $table->index(['brand_id', 'check_in']);
            $table->index(['brand_id', 'user_brand_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_visit', function (Blueprint $table) {
            $table->dropIndex(['brand_id', 'check_out', 'check_in', 'is_client']);
            $table->dropIndex(['brand_id', 'is_client']);
            $table->dropIndex(['brand_id', 'check_in']);
            $table->dropIndex(['brand_id', 'user_brand_id']);
            $table->index(['brand_id', 'check_out']);
        });
    }
}
