<?php

namespace App\Repositories\Satisfactions;

use App\QuestionResponse;

class QuestionResponseRepository implements QuestionResponseRepositoryInterface
{
    protected $questionResponse;

    public function __construct(QuestionResponse $questionResponse)
    {
        $this->questionResponse = $questionResponse;
    }

    public function create(int $questionId, bool $allowComment): QuestionResponse
    {
        return $this->questionResponse::create([
            'question_id' => $questionId,
            'allow_comment' => $allowComment
        ]);
    }
}
