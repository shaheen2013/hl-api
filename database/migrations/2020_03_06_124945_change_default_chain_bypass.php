<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema as Schema;

class ChangeDefaultChainBypass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hoteles', function (Blueprint $table) {
            $table->boolean('chain_bypass')->default(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hoteles', function (Blueprint $table) {
            $table->boolean('chain_bypass')->default(NULL)->change();
        });
    }
}
