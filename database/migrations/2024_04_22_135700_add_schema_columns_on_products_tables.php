<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSchemaColumnsOnProductsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('products', function (Blueprint $table) {
            $table->json('config_schema')->nullable(); 
        });

        Schema::table('brand_product', function (Blueprint $table) {
            $table->json('config')->nullable(); 
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('config_schema');
        });

        Schema::table('brand_product', function (Blueprint $table) {
            $table->dropColumn('config');
        });
    }
}
