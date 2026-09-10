<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\CategoryText;
use App\Question;
use App\QuestionBrand;
use App\QuestionText;

class CreateDefaultQuestionsAndCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create default questions and categories

        $defaultCategory = Category::firstOrCreate([
            "brand_id" => null
        ]);
        
        CategoryText::firstOrCreate([
            "category_id" => $defaultCategory->id,
            "lang_value" => "es",
            "text" => "N/A"
        ]);
        
        CategoryText::firstOrCreate([
            "category_id" => $defaultCategory->id,
            "lang_value" => "en",
            "text" => "N/A"
        ]);

        $defaultQuestion = Question::firstOrCreate([
            "category_id" => $defaultCategory->id
        ]);
        
        QuestionBrand::firstOrCreate([
            "brand_id" => null,
            "question_id" => $defaultQuestion->id,
            "required" => 1
        ]);
        
        QuestionText::firstOrCreate([
            "question_id" => $defaultQuestion->id,
            "lang_value" => "ca",
            "text" => "Què tal la seva experiència fins ara?"
        ]);
        
        QuestionText::firstOrCreate([
            "question_id" => $defaultQuestion->id,
            "lang_value" => "de",
            "text" => "Wie waren Ihre Erfahrungen bisher?"
        ]);
        
        QuestionText::firstOrCreate([
            "question_id" => $defaultQuestion->id,
            "lang_value" => "en",
            "text" => "How has your experience been so far?"
        ]);
        
        QuestionText::firstOrCreate([
            "question_id" => $defaultQuestion->id,
            "lang_value" => "es",
            "text" => "¿Qué tal su experiencia hasta ahora?"
        ]);
        
        QuestionText::firstOrCreate([
            "question_id" => $defaultQuestion->id,
            "lang_value" => "fr",
            "text" => "Comment s'est déroulée votre expérience jusqu'à présent ?"
        ]);
        
        QuestionText::firstOrCreate([
            "question_id" => $defaultQuestion->id,
            "lang_value" => "it",
            "text" => "Come è stata la sua esperienza finora?"
        ]);  
    }
}
