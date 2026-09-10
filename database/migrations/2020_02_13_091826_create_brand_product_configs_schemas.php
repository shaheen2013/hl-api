<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandProductConfigsSchemas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_config', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->string('label');
            $table->string('type');
            
            $table->foreign('product_id')->references('id')->on('products');
            $table->unique(['product_id', 'label']);
        });
        
        Schema::create('brand_product_config', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_product_id')->unsigned();
            $table->integer('product_config_id')->unsigned();
            $table->text('value');
            $table->timestamps();
            
            $table->foreign('brand_product_id')->references('id')->on('brand_product');
            $table->foreign('product_config_id')->references('id')->on('product_config');

            $table->unique(['product_config_id', 'brand_product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brand_product_config');
        Schema::dropIfExists('product_config');
    }
}
