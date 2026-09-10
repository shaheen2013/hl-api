<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveToQuestionBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_brand', function (Blueprint $table) {
            $table->tinyInteger('active')->after('question_id')->default(1);
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
            $table->dropColumn('active');
        });
    }
}
