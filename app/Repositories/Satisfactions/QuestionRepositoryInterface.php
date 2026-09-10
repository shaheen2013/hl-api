<?php

namespace App\Repositories\Satisfactions;

use App\Question;

interface QuestionRepositoryInterface
{
    /**
     * @param $surveyCategoryID
     * @return mixed
     */
    public function create(int $surveyCategoryId): Question;
    public function updatePosition(int $categoryId, int $questionId, int $position): void;
    public function decrementPositions(int $categoryId, int $deletedPosition): void;
}
