<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeleteCascadeOnUserSurveyIncidents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_survey_incident', function (Blueprint $table) {
            $table->dropForeign(['user_survey_id']);

            $table->foreign('user_survey_id')
                ->references('id')->on('user_survey')
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
        Schema::table('user_survey_incident', function (Blueprint $table) {
            $table->dropForeign(['user_survey_id']);

            $table->foreign('user_survey_id')
                ->references('id')->on('user_survey');
        });
    }
}
