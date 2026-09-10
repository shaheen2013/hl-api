<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrandAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_access', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->unsigned();
            $table->json('codes');
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands');
        });

        Artisan::call('db:seed', [
            '--class' => 'PopulateBrandAccess',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('brand_access');
    }
}
