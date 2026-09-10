<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\CategoryText;
use App\Question;
use App\QuestionBrand;
use App\QuestionText;
use App\HotelSatisfaction;
use App\Brand;
use App\Survey;
use App\SurveyQuestion;
use App\Satisfaction;
use App\SatisfactionAnswer;
use App\UserSurvey;
use App\UserSurveyQuestionAnswer;
use App\HotelStaff;

class RecoverChainSurveys extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultCategory = Category::where([
            "brand_id" => null
        ])->first();

        $defaultQuestion = Question::where([
            "category_id" => $defaultCategory->id
        ])->first();

        $chainSatisfaction = HotelSatisfaction::where(["customized_chain_activated" => 1])->get();

        $this->command->getOutput()->progressStart(count($chainSatisfaction));

        foreach ($chainSatisfaction as $satisfaction) {
            $brand = Brand::where('hotel_id', $satisfaction->id_hotel)->first();
            
            if ($brand && $brand->parent_id) {
                // Create chain survey
                $survey = $this->createSurvey($brand->parent_id);
    
                // Add generic question to chain survey
                $this->createSurveyQuestion($survey, $defaultQuestion->id);

                // Add all customized questions to chain survey
                $brandQuestions = QuestionBrand::where(['brand_id' => $brand->parent_id])->get();
                $this->createCustomizedSurveyQuestions($survey, $brandQuestions);

                SatisfactionAnswer::where(['brand_id' => $brand->id])->chunk(1000, function($customizedSatisfactions) use ($survey, $brand)
                {
                    foreach($customizedSatisfactions as $customizedSatisfaction) {
            
                        $userSurvey = UserSurvey::where([
                            "user_satisfaction_id" => data_get($customizedSatisfaction, 'user_satisfaction_id')
                        ])->first();

                        $userSurvey->survey_id = $survey->id;
                        $userSurvey->save();

                        $customizedQuestion = $this->createSurveyQuestion($survey, data_get($customizedSatisfaction, 'survey_question_id'));

                        $this->storeUserSurveyQuestionAnswer(
                            $customizedQuestion->id, 
                            $userSurvey->id, 
                            data_get($customizedSatisfaction, 'answer'), 
                            data_get($customizedSatisfaction, 'comment'), 
                            0, 
                            data_get($customizedSatisfaction, 'created_at'), 
                            data_get($customizedSatisfaction, 'created_at')
                        );
                    }
                });           
            }

            $this->command->getOutput()->progressAdvance();
        }
        
        $this->command->getOutput()->progressFinish();
    }

    private function createSurvey($brandID) {
        return Survey::firstOrCreate(
            [
                "brand_id" => $brandID
            ],
            [
                "name" => "Chain Survey"
            ]
        );
    }
    
    private function createSurveyQuestion($survey, $questionID, $required=1) {
        return SurveyQuestion::firstOrCreate(
            [
                "survey_id" => $survey->id,
                "question_id" => $questionID
            ],
            [
                "active" => 1,
                "required" => $required
            ]
        );
    }

    private function createCustomizedSurveyQuestions($survey, $brandQuestions) {
        foreach($brandQuestions as $brandQuestion) {
            $surveyQuestion = $this->createSurveyQuestion($survey, $brandQuestion->question_id, $brandQuestion->required);
        }
    }

    private function storeUserSurveyQuestionAnswer($questionID, $userSurveyID, $answer, $comment, $favorite, $created_at, $updated_at) {
        UserSurveyQuestionAnswer::firstOrCreate(
            [
                "survey_question_id" => $questionID,
                "user_survey_id" => $userSurveyID
            ],
            [
                "answer" => $answer,
                "comment" => $comment,
                "favorite" => $favorite,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            ]
        );
    }
}
