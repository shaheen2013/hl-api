<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnDeleteCascadeOnUserSurveyQuestionAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_survey_question_answer', function (Blueprint $table) {
            $table->dropForeign(['survey_question_id']);

            $table->foreign('survey_question_id')
                ->references('id')->on('survey_question')
                ->onDelete('cascade');
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
            $table->dropForeign(['survey_question_id']);

            $table->foreign('survey_question_id')
                ->references('id')->on('survey_question');
        });
    }
}
