<?php

namespace App\Services\Surveys;

use App\Repositories\Satisfactions\QuestionRepositoryInterface;
use App\Repositories\Satisfactions\QuestionBrandRepository;
use App\Repositories\Satisfactions\QuestionTextRepositoryInterface;
use App\Repositories\Satisfactions\QuestionResponseRepositoryInterface;
use App\Repositories\Satisfactions\QuestionResponseTextRepositoryInterface;
use App\Services\SatisfactionService;
use App\Services\BrandService;
use App\Types\Survey\UpdateQuestionDataType;
use Illuminate\Support\Facades\Log;

class SurveyQuestionService
{
    const MULTIRESPONSE_QUESTION_TYPE = 'Multiresponse';

    protected $questionRepositoryInterface;
    protected $questionBrandRepository;
    protected $questionTextRepositoryInterface;
    protected $questionResponseRepository;
    protected $questionResponseTextRepository;
    protected $surveyService;

    /**
     * @param QuestionRepositoryInterface $questionRepositoryInterface
     * @throws \Exception
     */
    public function __construct(
        QuestionRepositoryInterface $questionRepositoryInterface,
        QuestionBrandRepository $questionBrandRepository,
        QuestionTextRepositoryInterface $questionTextRepositoryInterface,
        SatisfactionService $surveyService,
        QuestionResponseRepositoryInterface $questionResponseRepositoryInterface,
        QuestionResponseTextRepositoryInterface $questionResponseTextRepositoryInterface
    ) {
        $this->questionRepositoryInterface = $questionRepositoryInterface;
        $this->questionBrandRepository = $questionBrandRepository;
        $this->questionTextRepositoryInterface = $questionTextRepositoryInterface;
        $this->questionResponseRepository = $questionResponseRepositoryInterface;
        $this->questionResponseTextRepository = $questionResponseTextRepositoryInterface;
        $this->surveyService = $surveyService;
    }

    /**
     * @param $brandId
     * @return \Illuminate\Cache\CacheManager|mixed
     * @throws \Exception
     */
    public function store($brandId, $data)
    {
        $question = $this->questionRepositoryInterface->create($data->surveyCategoryId);
        $questionBrand = $this->questionBrandRepository->create($brandId, $question->id, $data);

        // Add survey question id on the array of question translations
        $surveyQuestionsText = array_map(function ($questionText) use ($question) {
            return data_set($questionText, 'question_id', $question->id);
        }, $data->surveyQuestionsText);

        $this->questionTextRepositoryInterface->insert($surveyQuestionsText);

        if ($data->type === self::MULTIRESPONSE_QUESTION_TYPE) {
            foreach ($data->surveyAnswers as $answer) {
                $questionResponse = $this->questionResponseRepository->create($question->id, false);
                $this->questionResponseTextRepository->create($questionResponse->id, $answer);
            }

            if ($data->allowComment) {
                $questionResponse = $this->questionResponseRepository->create($question->id, $data->allowComment);
                $this->questionResponseTextRepository->create($questionResponse->id, config('customized_surveys.default_configuration.othersResponse'));
            }
        }

        $survey = $this->surveyService->getSurvey($brandId);
        if ($survey) {
            $this->surveyService->updateOrCreateSurveyQuestion($survey->id, $question->id, $data->required, 1);
        }

        return $questionBrand;
    }

    public function updateQuestionTexts(UpdateQuestionDataType $data): void
    {
        $questionBrand = $this->questionBrandRepository->getQuestionBrand($data->questionId, $data->brandId);

        if ($questionBrand) {
            foreach ($data->surveyQuestionsText as $questionText) {
                $this->questionTextRepositoryInterface->update($questionBrand->question_id, data_get($questionText, 'lang_value'), data_get($questionText, 'text'));
            }

            if ($data->surveyAnswers) {
                foreach ($data->surveyAnswers as $questionResponseText) {
                    $this->questionResponseTextRepository->update($questionResponseText);
                }
            }
        }
    }

    public function updateOrder($questionOrder): void
    {
        foreach ($questionOrder as $categoryId => $questionIds) {
            foreach ($questionIds as $index => $questionId) {
                $this->questionRepositoryInterface->updatePosition($categoryId, $questionId, $index + 1);
            }
        }
    }

    public function decrementPositions($deletedPosition, $categoryId): void
    {
        $this->questionRepositoryInterface->decrementPositions($categoryId, $deletedPosition);
    }
}
