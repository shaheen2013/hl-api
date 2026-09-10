<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIncidentsReviewedColumnOnUserSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_survey', function (Blueprint $table) {
            $table->boolean('incidents_reviewed')->default(0)->after('assisted_staff_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_survey', function (Blueprint $table) {
            $table->dropColumn('incidents_reviewed');
        });
    }
}
