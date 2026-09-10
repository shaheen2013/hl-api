<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_product', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brand_id');
            $table->integer('product_id');
            $table->boolean('active');

            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->unique(['brand_id', 'product_id'], 'brand_products_product_brand');
            $table->index(['brand_id', 'product_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brand_product');
    }
}
