<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSurveyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename("survey_categories", "category");
        Schema::rename("survey_categories_text", "category_text");
        Schema::rename("survey_questions_brand", "question_brand");
        Schema::rename("survey_questions", "question");
        Schema::rename("survey_questions_text", "question_text");

        Schema::table('category_text', function (Blueprint $table) {
            $table->renameColumn('survey_category_id', 'category_id');
        });
       
        Schema::table('question', function (Blueprint $table) {
            $table->renameColumn('survey_category_id', 'category_id');
        });
        
        Schema::table('question_brand', function (Blueprint $table) {
            $table->renameColumn('survey_question_id', 'question_id');
        });
        
        Schema::table('question_text', function (Blueprint $table) {
            $table->renameColumn('survey_question_id', 'question_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename("category", "survey_categories");
        Schema::rename("category_text", "survey_categories_text");
        Schema::rename("question_brand", "survey_questions_brand");
        Schema::rename("question", "survey_questions");
        Schema::rename("question_text", "survey_questions_text");

        $defaultCategory = DB::table("survey_categories")->whereNull('brand_id')->first();
        DB::table("survey_categories")->whereNull('brand_id')->delete();
        DB::table("survey_questions")->where('category_id', $defaultCategory->id)->delete();

        Schema::table('survey_categories_text', function (Blueprint $table) {
            $table->renameColumn('category_id', 'survey_category_id');
        });
       
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->renameColumn('category_id', 'survey_category_id');
        });
        
        Schema::table('survey_questions_brand', function (Blueprint $table) {
            $table->renameColumn('question_id', 'survey_question_id');
        });
        
        Schema::table('survey_questions_text', function (Blueprint $table) {
            $table->renameColumn('question_id', 'survey_question_id');
        });
    }
}
