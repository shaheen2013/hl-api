<?php

namespace App\Repositories\Satisfactions;

use App\QuestionBrand;

class QuestionBrandRepository
{
    protected $questionBrand;

    public function __construct(QuestionBrand $questionBrand)
    {
        $this->questionBrand = $questionBrand;
    }

    public function create($brandId, $questionId, $data)
    {
        return $this->questionBrand->create([
            'brand_id' => $brandId,
            'question_id' => $questionId,
            'required' => $data->required,
            'type' => $data->type,
            'allow_multiple_responses' => $data->multipleSelection
        ]);
    }

    public function getDefaultQuestion()
    {
        return $this->questionBrand->whereNull('brand_id')->first();
    }

    public function getByBrandID($brandID)
    {
        return $this->questionBrand->where(["brand_id" => $brandID])
            ->orWhereNull('brand_id')
            ->get();
    }

    public function getQuestionBrand(int $questionId, int $brandId): QuestionBrand
    {
        return $this->questionBrand->where([
            'question_id'   => $questionId,
            'brand_id'      => $brandId
        ])->firstOrFail();
    }
}
