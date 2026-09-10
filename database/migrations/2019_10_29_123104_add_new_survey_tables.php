<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewSurveyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('brand_id')->unsigned();
            $table->string('name');
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->index(['brand_id']);
        });
        
        Schema::create('survey_question', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('survey_id');
            $table->integer('question_id')->unsigned();
            $table->tinyInteger('active');
            $table->tinyInteger('required');

            $table->foreign('survey_id')->references('id')->on('survey')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('question_id')->references('id')->on('question')->onUpdate('RESTRICT')->onDelete('CASCADE');

            $table->index(['survey_id']);
            $table->index(['survey_id', 'question_id']);

            $table->unique(['survey_id', 'question_id']);


        });
        
        Schema::create('user_survey', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('survey_id');
            $table->integer('brand_id')->unsigned();
            $table->integer('user_id');
            $table->string('access_code')->nullable();
            $table->boolean('assisted');
            $table->integer('assisted_staff_id')->nullable();
            $table->boolean('review_sent')->default(0);
            $table->datetime('send_date');
            $table->dateTime('customized_send_date')->nullable();
            $table->timestamps();
            $table->integer('user_satisfaction_id');

            $table->foreign('survey_id')->references('id')->on('survey')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('brand_id')->references('id')->on('brands')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('assisted_staff_id')->references('id')->on('hotel_staff')->onDelete('set null');
            $table->foreign('user_satisfaction_id')->references('id')->on('user_satisfaction')->onUpdate('RESTRICT')->onDelete('CASCADE');
            
            $table->index(['brand_id', 'user_id']);
            $table->index(['brand_id', 'user_id', 'created_at']);
        });

        Schema::create('user_survey_question_answer', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('survey_question_id');
            $table->integer('user_survey_id');

            $table->float('answer')->nullable();
            $table->text('comment')->nullable();
           
            $table->boolean('favorite')->default(0);

            $table->timestamps();

            $table->foreign('survey_question_id')->references('id')->on('survey_question');
            
            $table->index(['survey_question_id']);
            $table->index(['created_at']);
            $table->index(['answer', 'created_at']);
        });

        Schema::table('question', function (Blueprint $table) {
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
        Schema::dropIfExists('user_survey_question_answer');
        Schema::dropIfExists('user_survey');
        Schema::dropIfExists('survey_question');
        Schema::dropIfExists('survey');

        Schema::table('question', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
