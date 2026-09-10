<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandOfferWifi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_offer_wifi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->unsigned();
            $table->integer('offer_id');
            $table->enum('condition', ['facebook_login', 'always', 'facebook_share'])->default('facebook_share');
            $table->enum('offer_type', ['inmediate', 'web'])->default('web');
            $table->integer('period')->default(30);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('offer_id')->references('id')->on('hotel_oferta');

            $table->index(['brand_id', 'valid_from', 'valid_to']);
        });

        Artisan::call('db:seed', [
            '--class' => 'PopulateBrandOfferWifi',
            '--force' => true
        ]);

        Schema::rename('hotel_oferta_wifi', 'old_hotel_oferta_wifi');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('brand_offer_wifi');
        Schema::rename('old_hotel_oferta_wifi', 'hotel_oferta_wifi');
    }
}
