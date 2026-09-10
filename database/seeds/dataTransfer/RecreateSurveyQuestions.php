<?php

use App\BrandProduct;
use App\QuestionBrand;
use App\SurveyQuestion;
use App\SatisfactionAnswer;
use App\UserSurvey;
use App\UserSurveyQuestionAnswer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RecreateSurveyQuestions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $corruptedCustomizedSurveys = BrandProduct::join('survey', 'survey.brand_id', '=', 'brand_product.brand_id')
            ->join('survey_question', 'survey.id', '=', 'survey_question.survey_id')
            ->where([
                'product_id' => 15,
                'brand_product.active' => 1
            ])
            ->groupBy('survey.id')
            ->get();

        foreach ($corruptedCustomizedSurveys as $customizedSurvey) {
            $questionsBrand = QuestionBrand::where(['brand_id' => $customizedSurvey->brand_id])->get();
            $customizedAnswers = SatisfactionAnswer::where(['brand_id' => $customizedSurvey->brand_id])->get();

            foreach ($questionsBrand as $questionBrand) {
                SurveyQuestion::firstOrCreate(
                    [
                        'survey_id' => $customizedSurvey->survey_id,
                        'question_id' =>  $questionBrand->question_id
                    ],
                    [
                        'active' => 1,
                        'required' => 1,
                    ]
                );
            }
        }

        $this->command->getOutput()->progressStart(SatisfactionAnswer::count());

        SatisfactionAnswer::chunk(1000, function($satisfactionAnswers)
        {
            foreach ($satisfactionAnswers as $satisfactionAnswer)
            {
                $userSurvey = UserSurvey::where([
                    'user_satisfaction_id' => $satisfactionAnswer->user_satisfaction_id
                ])->first();

                $surveyQuestion = SurveyQuestion::where('question_id', data_get($satisfactionAnswer, 'survey_question_id'))->first();

                if ($userSurvey && $surveyQuestion) {
                    UserSurveyQuestionAnswer::firstOrCreate(
                        [
                            "survey_question_id" => $surveyQuestion->id,
                            "user_survey_id" => $userSurvey->id
                        ],
                        [
                            "answer" => $satisfactionAnswer->answer,
                            "comment" => $satisfactionAnswer->comment,
                            "favorite" => 0,
                            "created_at" => $satisfactionAnswer->created_at,
                            "updated_at" => $satisfactionAnswer->created_at
                        ]
                    );
                }

                
                $this->command->getOutput()->progressAdvance();
            }
        });

        $this->command->getOutput()->progressFinish();
    }
}
