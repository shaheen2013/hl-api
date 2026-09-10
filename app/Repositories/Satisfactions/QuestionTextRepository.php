<?php

namespace App\Repositories\Satisfactions;

use App\QuestionText;

class QuestionTextRepository implements QuestionTextRepositoryInterface
{
    protected $questionText;


    public function __construct(QuestionText $questionText)
    {
        $this->questionText = $questionText;
    }

    public function insert(array $surveyQuestionsText)
    {
        $this->questionText->insert($surveyQuestionsText);
    }

    public function update(int $questionId, string $lang, string $text): void
    {
        $this->questionText->updateOrCreate(
            [
                'question_id'   => $questionId,
                'lang_value'    => $lang
            ],
            [
                'text'          => $text
            ]
        );
    }
}
