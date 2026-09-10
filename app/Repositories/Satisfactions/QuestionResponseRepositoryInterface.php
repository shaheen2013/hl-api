<?php

namespace App\Repositories\Satisfactions;

use App\QuestionResponse;

interface QuestionResponseRepositoryInterface
{
    /**
     * @param int $questionId
     * @param string $type
     * @return mixed
     */
    public function create(int $questionId, bool $allowComment): QuestionResponse;
}
