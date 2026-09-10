<?php

namespace App\Http\Resources\Surveys;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @property mixed hotel
 * @property mixed id
 */
class SurveyQuestionsResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $surveyQuestions = [];
        foreach ($this->question as $surveyQuestion) {
            if (!$request->get('active') || ($request->get('active') && Arr::get($surveyQuestion->toArray(), 'question_brand.0.active'))) {
                if (!$request->get('lang')) {
                    $questionsText = [];
                    foreach ($surveyQuestion->questionText as $surveyText) {
                        $questionsText[$surveyText->lang_value] = $surveyText->text;
                    }
                } else {
                    $questionsText = data_get($surveyQuestion, 'questionText.0.text');
                }

                $questionResponses = [];
                foreach ($surveyQuestion->questionResponse as $questionResponse) {
                    if (!$request->get('lang')) {
                        $response = [];

                        $response['id'] = Arr::get($questionResponse->toArray(), 'id');
                        foreach ($questionResponse->questionResponseText as $questionResponseText) {
                            $response[$questionResponseText->lang_value] = $questionResponseText->text;
                        }
                    } else {
                        $response['id'] = Arr::get($questionResponse->toArray(), 'id');
                        $response['allowComment'] = Arr::get($questionResponse->toArray(), 'allow_comment');
                        $response['text'] = Arr::get($questionResponse->toArray(), 'question_response_text.0.text');
                    }

                    $questionResponses [] = $response;
                }

                $surveyQuestions[] = [
                    'id' => $surveyQuestion->id,
                    'position' => $surveyQuestion->position,
                    'active' => Arr::get($surveyQuestion->toArray(), 'question_brand.0.active'),
                    'required' => Arr::get($surveyQuestion->toArray(), 'question_brand.0.required'),
                    'type' => Arr::get($surveyQuestion->toArray(), 'question_brand.0.type'),
                    'allow_multiple_responses' => Arr::get($surveyQuestion->toArray(), 'question_brand.0.allow_multiple_responses'),
                    'question_text' => $questionsText,
                    'question_responses' => $questionResponses,
                ];
            }
        }

        if (!$request->get('lang')) {
            $categoryText = [];
            foreach ($this->categoryText as $surveyCategory) {
                $categoryText[$surveyCategory->lang_value] = $surveyCategory->text;
            }
        } else {
            $categoryText = data_get($this, 'categoryText.0.text');
        }

        return  [
            'id' => $this->id,
            'position' => $this->position,
            'category_text' => $categoryText,
            'questions' => $surveyQuestions
        ];
    }
}
