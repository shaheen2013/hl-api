<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPremiumCodeCloumnOnPortalProConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('portal_pro_config', function (Blueprint $table) {
            $table->boolean('premium_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('portal_pro_config', function (Blueprint $table) {
            $table->dropColumn('premium_code');
        });
    }
}
