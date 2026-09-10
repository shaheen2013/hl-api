<?php

namespace App\Repositories\Satisfactions;

use App\QuestionResponseText;

interface QuestionResponseTextRepositoryInterface
{
    /**
     * @param int $questionResponseId
     * @param array $answer
     * @return mixed
     */
    public function create(int $questionResponseId, array $answers): void;
    public function update(array $answers): void;
}
