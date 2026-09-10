<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToImproveClientsQuery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_user_brand', function (Blueprint $table) {
            $table->index(['brand_id', 'user_id']);
            $table->index(['brand_id', 'unsubscribed', 'user_id']);
            $table->index(['brand_id', 'date', 'user_id']);
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
            $table->dropIndex(['brand_id', 'user_id']);
            $table->dropIndex(['brand_id', 'unsubscribed', 'user_id']);
            $table->dropIndex(['brand_id', 'date', 'user_id']);
        });
    }
}
