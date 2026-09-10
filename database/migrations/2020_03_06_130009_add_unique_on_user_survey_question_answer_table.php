<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


class AddUniqueOnUserSurveyQuestionAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TABLE tmp_user_survey_question_answer LIKE user_survey_question_answer');

        Schema::table('tmp_user_survey_question_answer', function (Blueprint $table) {
            $table->unique(['survey_question_id', 'user_survey_id'], 'survey_question_answer_unique');
        });

        DB::statement('INSERT IGNORE INTO tmp_user_survey_question_answer SELECT * FROM user_survey_question_answer');

        Schema::rename('user_survey_question_answer', 'old_user_survey_question_answer');
        Schema::rename('tmp_user_survey_question_answer', 'user_survey_question_answer');

        Schema::drop('old_user_survey_question_answer');

        Schema::table('user_survey_question_answer', function (Blueprint $table) {
            $table->foreign('survey_question_id')->references('id')->on('survey_question');
            $table->foreign('user_survey_id')->references('id')->on('user_survey');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_survey_question_answer', function ($table) {
            $table->dropUnique('survey_question_answer_unique');
        });
    }
}
