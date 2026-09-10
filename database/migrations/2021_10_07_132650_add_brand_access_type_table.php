<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrandAccessTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_access_type', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brand_id')->nullable();
            $table->unsignedInteger('access_type_id');
            $table->boolean('active');

            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('access_type_id')->references('id')->on('new_access_type')->onDelete('cascade');

            $table->unique(['brand_id', 'access_type_id']);

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
        Schema::dropIfExists('brand_access_type');
    }
}
