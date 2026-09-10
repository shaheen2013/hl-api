<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSurveyIncidentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_survey_incident', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_survey_id');
            $table->integer('hotel_staff_id')->nullable();
            $table->text('incident_text');
            $table->timestamps();

            $table->foreign('user_survey_id')->references('id')->on('user_survey');
            $table->foreign('hotel_staff_id')->references('id')->on('hotel_staff');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_survey_incident');
    }
}
