<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuestionResponseIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customized_satisfaction_answers', function (Blueprint $table) {
            $table->integer('question_response_id')->unsigned()->nullable()->after('survey_question_id');
            $table->foreign('question_response_id')->references('id')->on('question_response');
        });
        
        Schema::table('user_survey_question_answer', function (Blueprint $table) {
            $table->integer('question_response_id')->unsigned()->nullable()->after('user_survey_id');
            $table->foreign('question_response_id')->references('id')->on('question_response');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_survey_question_answer', function (Blueprint $table) {
            $table->dropForeign(['question_response_id']);
            $table->dropColumn('question_response_id');
        });
        
        Schema::table('customized_satisfaction_answers', function (Blueprint $table) {
            $table->dropForeign(['question_response_id']);
            $table->dropColumn('question_response_id');
        });
    }
}
