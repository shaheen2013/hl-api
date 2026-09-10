<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeOnQuestionBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_brand', function (Blueprint $table) {
            $table->enum('type', ['Rating', 'Multiresponse']);
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
            $table->dropColumn('type');
        });
    }
}
