<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueIndexOnUserSurveyQuestionAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_survey_question_answer', function ($table) {
            $table->dropUnique('survey_question_answer_unique');
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
            $table->unique(['survey_question_id', 'user_survey_id'], 'survey_question_answer_unique');
        });

    }
}
