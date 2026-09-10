<?php

namespace App\Repositories\Satisfactions;

interface QuestionTextRepositoryInterface
{
    /**
     * @param $surveyCategoryID
     * @return mixed
     */
    public function insert(array $surveyQuestionsText);
    public function update(int $questionId, string $lang, string $text): void;
}
