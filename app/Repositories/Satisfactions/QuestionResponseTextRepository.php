<?php

namespace App\Repositories\Satisfactions;

use App\QuestionResponseText;
use Illuminate\Support\Arr;

class QuestionResponseTextRepository implements QuestionResponseTextRepositoryInterface
{
    protected $questionResponseText;

    public function __construct(QuestionResponseText $questionResponseText)
    {
        $this->questionResponseText = $questionResponseText;
    }

    public function create(int $questionResponseId, array $answers): void
    {
        foreach ($answers as $answer) {
            $this->questionResponseText->create([
                "question_response_id" => $questionResponseId,
                "lang_value" => Arr::get($answer, 'lang_value'),
                "text" => Arr::get($answer, 'text')
            ]);
        }
    }

    public function update(array $answers): void
    {
        foreach ($answers as $answer) {
            $this->questionResponseText->updateOrCreate(
                [
                    'question_response_id'  => Arr::get($answer, 'question_response_id'),
                    'lang_value'            => Arr::get($answer, 'lang_value')
                ],
                [
                    'text'                  => Arr::get($answer, 'text')
                ]
            );
        }
    }
}
