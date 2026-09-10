<?php

namespace App\Repositories\Satisfactions;

use App\Question;

class QuestionRepository implements QuestionRepositoryInterface
{
    protected $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    public function create(int $surveyCategoryId): Question
    {
        $lastPosition = $this->question->where('category_id', $surveyCategoryId)->max('position');
        $newPosition = $lastPosition + 1;

        return $this->question::create([
            'category_id' => $surveyCategoryId,
            'position' => $newPosition
        ]);
    }

    public function updatePosition(int $categoryId, int $questionId, int $position): void
    {
        $this->question->where([
            'category_id' => $categoryId,
            'id' => $questionId
        ])->update(['position' => $position]);
    }

    public function decrementPositions(int $categoryId, int $deletedPosition): void
    {
        $this->question->where('category_id', $categoryId)
        ->where('position', '>', $deletedPosition)
        ->decrement('position');
    }
}
