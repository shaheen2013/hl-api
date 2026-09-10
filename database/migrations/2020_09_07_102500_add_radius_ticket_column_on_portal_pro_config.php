<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRadiusTicketColumnOnPortalProConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('portal_pro_config', function (Blueprint $table) {
            $table->boolean('radius_ticket')->default(false);
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
            $table->dropColumn('radius_ticket');
        });
    }
}
