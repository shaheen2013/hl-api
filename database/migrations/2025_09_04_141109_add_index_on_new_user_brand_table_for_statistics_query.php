<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexOnNewUserBrandTableForStatisticsQuery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_user_brand', function (Blueprint $table) {
            $table->index(['brand_id', 'date', 'user_gender']);
            $table->index(['brand_id', 'date', 'user_country']);
            $table->index(['brand_id', 'date', 'user_generation']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_user_brand', function (Blueprint $table) {
            $table->dropIndex(['brand_id', 'date', 'user_gender']);
            $table->dropIndex(['brand_id', 'date', 'user_country']);
            $table->dropIndex(['brand_id', 'date', 'user_generation']);
        });
    }
}
