<?php

namespace App\Services\Products;

use App\Repositories\Satisfactions\SatisfactionRepositoryInterface;

class SatisfactionService
{
    protected $satisfactionRepository;

    public function __construct(SatisfactionRepositoryInterface $satisfactionRepository)
    {
        $this->satisfactionRepository = $satisfactionRepository;
    }

    public function parseComments($fromID, $toID = null)
    {
        $satisfactions = $this->satisfactionRepository->getFromRangeId($fromID, $toID);

        foreach ($satisfactions->cursor() as $satisfaction) {
            $comment = json_decode($satisfaction->comment, true);
            if (is_string($comment)) {
                $satisfaction->comentario = $comment;
                $satisfaction->save();
            }
        }
    }
}
