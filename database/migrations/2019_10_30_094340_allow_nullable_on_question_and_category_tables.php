<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowNullableOnQuestionAndCategoryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_brand', function (Blueprint $table) {
            $table->integer('brand_id')->nullable()->unsigned()->change();
        });
        
        Schema::table('category', function (Blueprint $table) {
            $table->integer('brand_id')->nullable()->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
