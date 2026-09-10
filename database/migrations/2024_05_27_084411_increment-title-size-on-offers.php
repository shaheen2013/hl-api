<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncrementTitleSizeOnOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_oferta_lang', function (Blueprint $table) {
            $table->string('nombre', 200)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_oferta_lang', function (Blueprint $table) {
            $table->string('nombre', 100)->change();
        });
    }
}
