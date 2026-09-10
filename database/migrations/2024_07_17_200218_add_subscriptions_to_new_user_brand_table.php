<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionsToNewUserBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_user_brand', function (Blueprint $table) {
            $table->json('subscriptions')->nullable();
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
            $table->dropColumn('subscriptions');
        });
    }
}
