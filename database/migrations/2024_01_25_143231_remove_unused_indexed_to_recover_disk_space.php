<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnusedIndexedToRecoverDiskSpace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('users_visits', function (Blueprint $table) {
            $table->dropIndex("user_constrain_id_idx");
        });

        Schema::table('user_survey', function (Blueprint $table) {
            $table->dropIndex(['brand_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_survey', function (Blueprint $table) {
            $table->index(['brand_id', 'user_id']);
        });

        Schema::table('users_visits', function (Blueprint $table) {
            $table->index(['user_id'], "user_constrain_id_idx");
        });
    }
}
