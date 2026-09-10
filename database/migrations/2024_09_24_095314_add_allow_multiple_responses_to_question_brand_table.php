<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllowMultipleResponsesToQuestionBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_brand', function (Blueprint $table) {
            $table->boolean('allow_multiple_responses')->default(0)->after('required');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_brand', function (Blueprint $table) {
            $table->dropColumn('allow_multiple_responses');
        });
    }
}
