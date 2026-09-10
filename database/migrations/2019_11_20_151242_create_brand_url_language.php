<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandUrlLanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_url_language', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('language_id');
            $table->text('url')->collation('utf8_unicode_ci');

            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('language_id')->references('id')->on('languages');

    
            $table->unique(['brand_id', 'language_id'], 'test');

            $table->index(['brand_id', 'language_id']);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

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
        Schema::dropIfExists('brand_url_language');
    }
}
