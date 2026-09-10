<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuestionResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_response', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('question_id')->unsigned();
            $table->boolean('allow_comment');
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('question')->onDelete('CASCADE');;
        });
        
        Schema::create('question_response_text', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('question_response_id')->unsigned();
            $table->string('lang_value', 6);
            $table->text('text');
            $table->timestamps();

            $table->foreign('question_response_id')->references('id')->on('question_response')->onDelete('CASCADE');;
            $table->foreign('lang_value')->references('name')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_response_text');
        Schema::dropIfExists('question_response');
    }
}
