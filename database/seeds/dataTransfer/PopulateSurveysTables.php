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
use App\UserSurvey;
use App\UserSurveyQuestionAnswer;
use App\HotelStaff;

class PopulateSurveysTables extends Seeder
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
        
        $satisfactions = HotelSatisfaction::all();
        $this->command->getOutput()->progressStart(count($satisfactions));

        foreach ($satisfactions as $satisfaction) {
            $brand = Brand::where('hotel_id', $satisfaction->id_hotel)->first();
            
            if ($brand) {
                $survey = $this->createSurvey($brand->id);
                $surveyQuestion = $this->createSurveyQuestion($survey, $defaultQuestion->id);

                $brandQuestions = QuestionBrand::where(['brand_id' => $brand->id])->get();
                $this->createCustomizedSurveyQuestions($survey, $brandQuestions);

                $parentSurvey = null;
                if ($satisfaction->customized_chain_activated) {
                    $parentSurvey = $this->createSurvey($brand->parent_id, "Chain Survey");
                    $this->createSurveyQuestion($parentSurvey, $defaultQuestion->id);
                    
                    $parentQuestions = QuestionBrand::where(['brand_id' => $brand->parent_id])->get();
                    $this->createCustomizedSurveyQuestions($parentSurvey, $parentQuestions);
                }

                Satisfaction::where(['id_hotel' => $satisfaction->id_hotel])->chunk(1000, function($satisfactions) use ($survey, $parentSurvey, $surveyQuestion, $brand)
                {
                    foreach($satisfactions as $satisfaction) {
                        $survey = $parentSurvey ? $parentSurvey : $survey;
                        $staff = HotelStaff::where('id', data_get($satisfaction, 'who_has_been_seen'))->first();
            
                        $userSurvey = UserSurvey::firstOrcreate(
                            [
                                "user_satisfaction_id" => data_get($satisfaction, 'id')
                            ],
                            [
                                "survey_id" => $survey->id,
                                "user_id" => data_get($satisfaction, 'id_usuario', data_get($satisfaction, 'user_id')),
                                "brand_id" => $brand->id,
                                "access_code" => data_get($satisfaction, 'id_room'),
                                "assisted" => data_get($satisfaction, 'has_been_seen', 0),
                                "assisted_staff_id" => data_get($satisfaction, 'who_has_been_seen') != -1 ? data_get($staff, 'id') : null,
                                "review_sent" => data_get($satisfaction, 'review_send', 0),
                                "send_date" => data_get($satisfaction, 'send_date'),
                                "customized_send_date" => data_get($satisfaction, 'customized_send_date'),
                                "created_at" => data_get($satisfaction, 'fecha_creado'),
                                "updated_at" => data_get($satisfaction, 'fecha_creado'),
                            ]
                        );
            
                        if (data_get($satisfaction, 'done')) {
                            $this->storeUserSurveyQuestionAnswer($surveyQuestion->id, $userSurvey->id, data_get($satisfaction, 'puntuacion'), data_get($satisfaction, 'comentario'),  data_get($satisfaction, 'favorite'), data_get($satisfaction, 'fecha_update'), data_get($satisfaction, 'fecha_update'));
                        }
                        
                        foreach (data_get($satisfaction, 'satisfactionAnswer') as $customizedAnswers) {
                            $customizedQuestion = SurveyQuestion::where('question_id', data_get($customizedAnswers, 'survey_question_id'))->first();
                            $this->storeUserSurveyQuestionAnswer($customizedQuestion->id,  $userSurvey->id, data_get($customizedAnswers, 'answer'), data_get($customizedAnswers, 'comment'), 0, data_get($customizedAnswers, 'created_at'), data_get($customizedAnswers, 'created_at'));
                        }
                    }
                });           
            }

            $this->command->getOutput()->progressAdvance();
        }
        
        $this->command->getOutput()->progressFinish();
    }

    private function createSurvey($brandID, $name="Default Survey") {
        return Survey::firstOrCreate(
            [
                "brand_id" => $brandID
            ],
            [
                "name" => $name
            ]
        );
    }
    
    private function createSurveyQuestion($survey, $questionID, $required=1) {
        return SurveyQuestion::firstOrCreate([
            "survey_id" => $survey->id,
            "question_id" => $questionID
        ],
        [
            "active" => 1,
            "required" => $required
        ]);
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
