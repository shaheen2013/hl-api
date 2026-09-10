<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexOnNewVisitTableForClientsCountQuery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_visit', function (Blueprint $table) {
            $table->index(['brand_id', 'check_in', 'user_id']);
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
            $table->dropIndex(['brand_id', 'check_in', 'user_id']);
        });
    }
}
