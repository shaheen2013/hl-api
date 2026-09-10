<?php

namespace App\Jobs;

use App\Services\Users\UserService;
use App\Services\Users\UserBrandService;
use App\Services\Visits\VisitService;
use App\Services\BrandService;
use App\Services\Connections\ConnectionHistoryService;
use App\Services\Products\SatisfactionSurveyProductService;
use App\Repositories\Satisfactions\CustomizedSatisfactionAnswerRepository;
use App\Services\Emails\ReviewEmailService;
use App\Services\Emails\SatisfactionEmailThanksService;
use App\Types\Survey\SaveSurveyAnswersDataType;
use App\Events\Surveys\SurveyQuestionAnsweredEvent;
use Hotelinking\Services\ApiGatewayConnection;
use Hotelinking\Services\SchemaValidator;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class ProcessSaveSurveyAnswer implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $data;
    protected $gatewayConnection;
    protected $satisfactionService;
    protected $customizedSurveyService;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SaveSurveyAnswersDataType $data)
    {
        $schemaValidator = app(SchemaValidator::class);

        $this->data = $data;
        $this->gatewayConnection = new ApiGatewayConnection($schemaValidator);
        $this->satisfactionService = app('App\Services\SatisfactionService');
        $this->customizedSurveyService = app('App\Services\Products\CustomizedSurveyProductService');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(BrandService $brandService, UserService $userService, ConnectionHistoryService $connectionService, CustomizedSatisfactionAnswerRepository $customizedSatisfactionAnswerRepository, SatisfactionSurveyProductService $satisfactionSurveyProductService, UserBrandService $userBrandService, VisitService $visitService)
    {
        try {
            // Save answer in new Survey system tables
            $brand = $brandService->getBrand($this->data->brandId);
            $user = $userService->getUser($this->data->userId);
            $userSurvey = $this->satisfactionService->getUserSurvey($this->data->surveyId);
            $defaultQuestion = $this->satisfactionService->getDefaultSurveyQuestion($userSurvey->survey_id);
            $surveyConfiguration = $this->customizedSurveyService->getConfiguration($brand->id)->toArray(null);
            $lastConnection = $connectionService->getLastConnection($brand->hotel->id, $user->id);
            $lastLogin = $lastConnection ? $lastConnection->last_login : data_get($userSurvey, 'created_at');
            $genericResponse = $defaultQuestion->userSurveyQuestionAnswers->first(function ($value, $key) use ($userSurvey) {
                return $value->user_survey_id == $userSurvey->id;
            });
            $userBrand = $userBrandService->get($brand->id, $user->id);
            $relatedVisit = $visitService->getVisit($userBrand->id, Carbon::parse($userSurvey->created_at)->addHour());
            if ($userSurvey) {
                Log::info("SAVE SURVEY ANSWER INFO: Saving data in new table system", ["brand" => $this->data->brandId, "user_survey" => $userSurvey, "data" => $this->data->getAttributes()]);

                $questionId = $this->data->questionId ? $this->data->questionId : $defaultQuestion->question_id;
                $surveyQuestion = $this->satisfactionService->getSurveyQuestion($userSurvey->survey_id, $questionId);

                if ($surveyQuestion) {
                    $responseIds = $this->data->responseIds ? $this->data->responseIds : [null];
                    foreach ($responseIds as $responseId) {
                        $this->satisfactionService->saveUserSurveyQuestionAnswer($userSurvey->id, $surveyQuestion->id, $this->data->score, $this->data->comment, $responseId);
                    }
                    Cache::tags("surveys-$brand->id")->flush();
                } else {
                    Log::warning("Save Survey Answer warn: no survey question found", [
                        "Brand"             => $brand,
                        "User"              => $user,
                        "User Survey"       => $userSurvey,
                        "Generic Response"  => $genericResponse,
                        "Question Id"       => $questionId,
                        "Data"              => $this->data->getAttributes()
                    ]);
                }
            } else {
                Log::warning("Save Survey Answer warn: no user survey found", [
                    "Brand"             => $brand,
                    "User"              => $user,
                    "Generic Response"  => $genericResponse,
                    "Data"              => $this->data->getAttributes()

                ]);
            }
            // Save answer in old survey system tables
            if (!$genericResponse) {
                Log::info("SAVE SURVEY ANSWER INFO: Saving data in old table system, generic question", ["brand" => $this->data->brandId, "data" => $this->data->getAttributes()]);

                // Send old events
                $survey = $satisfactionSurveyProductService->getSurvey($this->data->brandId, $this->data->surveyId);
                $genericSurveyConfiguration = (object) $satisfactionSurveyProductService->getConfiguration($this->data->brandId)->toArray(null);
                $surveyAnswer = $this->getSurveysAnswer($this->data->score, $this->data->comment);
                $summary = null;
                $type = "generic";

                $this->sendThanksEmail($survey, $genericSurveyConfiguration, $surveyAnswer, $brand, $user);
                $this->sendReviewEmail($survey, $genericSurveyConfiguration, $surveyAnswer, $brand, $user, $this->data->surveyId, $userSurvey, $relatedVisit);

                // Sava data
                $this->satisfactionService->saveUserSatisfaction($this->data->surveyId, $this->data->score, $this->data->comment);
            } elseif ($this->data->questionId) {
                Log::info("SAVE SURVEY ANSWER INFO: Saving data in old table system, customized question", ["brand" => $this->data->brandId, "data" => $this->data->getAttributes()]);

                $customizedSatisfactionAnswerRepository->firstOrCreate($this->data);

                // Send survey updated event
                $questions = $this->customizedSurveyService->getQuestions($brand->id, 'en');
                $answers = $this->customizedSurveyService->getAnswers($brand->id, $this->data->surveyId);
                $summary = $this->createCustomizedSatisfactionSummary($questions, $answers);
                $type = "customized";
            }

            event(new SurveyQuestionAnsweredEvent([
                "brand"  => [
                    "id"                    => $brand->id,
                    "hotelId"               => $brand->hotel->id,
                    "name"                  => $brand->hotel->name,
                ],
                "user" => [
                    "id"                    => $user->id,
                    "name"                  => $user->name,
                    "email"                 => $user->email,
                    "lang"                  => $user->language->name ?? 'Unknown',
                    "gender"                => $user->gender,
                    "birthDate"             => Carbon::parse($user->birthday)->format('Y-m-d'),
                    "firstLogin"            => Carbon::parse(data_get($userSurvey, 'created_at'))->format('Y-m-d'),
                    "lastLogin"             => Carbon::parse($lastLogin)->format('Y-m-d'),
                    "accessCode"            => data_get($userSurvey, 'access_code'),
                    "reservation"           => data_get($relatedVisit, 'reservation')
                ],
                "survey" => [
                    "type"                  => $type,
                    "score"                 => $this->data->score,
                    "comment"               => $this->data->comment,
                    "generic"               =>  [
                        "score"     => data_get($genericResponse, 'answer', $this->data->score),
                        "comment"   => data_get($genericResponse, 'comment', $this->data->comment)
                    ],
                    "summary"               => $summary,
                    "createdAt"             => Carbon::parse(data_get($userSurvey, 'created_at'))->format('Y-m-d'),
                    "sentAt"                => Carbon::parse(data_get($userSurvey, 'created_at'))
                        ->addDays(data_get($surveyConfiguration, 'sendAfterDays'))
                        ->addHours(data_get($surveyConfiguration, 'sendHour'))
                        ->format('Y-m-d'),
                    "filledAt"              => Carbon::now()->format('Y-m-d'),
                    "config" => [
                        "emails"            => explode(",", data_get($surveyConfiguration, 'warningEmail')),
                        "sendWarningAlways" => !data_get($surveyConfiguration, 'filterWarning'),
                        "customizedWarning" => data_get($surveyConfiguration, 'customizedWarning'),
                        "minScore"          => data_get($surveyConfiguration, 'minimumScore'),
                        "customizedActive"  => data_get($surveyConfiguration, 'customizedActive'),
                        "customizedType"    => data_get($surveyConfiguration, 'customizedType'),
                    ]
                ]
            ]));
        } catch (\Throwable $e) {
            Log::error("Worker Error", ["error" => $e]);
        }
    }

    private function createCustomizedSatisfactionSummary($questions, $answers)
    {
        return Arr::collapse(
            array_map(function ($question) use ($answers) {
                $questionsInfo = Arr::get($question, 'questions');
                $questionsInfo = array_filter($questionsInfo, function ($val) {
                    return data_get($val, 'active');
                });

                return array_map(function ($questionInfo) use ($answers, $question) {
                    $answer = Arr::first($answers, function ($answer) use ($questionInfo) {
                        return data_get($answer, 'survey_question_id') == data_get($questionInfo, 'id');
                    });

                    if (data_get($answer, 'question_response_id')) {
                        $response = Arr::first($answer->questionResponse->questionResponseText, function ($value, $key) {
                            return $value->lang_value == 'en';
                        })->text;
                    } else {
                        $response = $answer ?
                            data_get($answer, 'answer', 'NR/DK') :
                            "N/R";
                    }


                    return [
                        "category" => data_get($question, 'category_text'),
                        "question" => data_get($questionInfo, 'question_text'),
                        "answer" => $response,
                        "comment" => !empty(data_get($answer, 'comment')) ?
                            data_get($answer, 'comment') : ($response === "NR/DK" ?
                                'NR/DK' :
                                'N/R')
                    ];
                }, $questionsInfo);
            }, $questions->toArray(
                new Request([
                    'lang'   => 'en'
                ])
            ))
        );
    }


    private function sendReviewEmail($survey, $surveyConfiguration, $surveyAnswer, $brand, $user, $satisfactionId, $userSurvey, $visit)
    {
        $reviewConfigurationService = app('App\Services\Products\ReviewProductService');
        $reviewConfiguration = (object) $reviewConfigurationService->getConfiguration($brand->id)->toArray(null);

        if (!$survey->done) {
            if ($reviewConfiguration->active && $surveyConfiguration->reviewAverageScore <= $surveyAnswer->score && !$reviewConfiguration->ignore_rating) {
                $reviewService = new ReviewEmailService($this->gatewayConnection);
                $reviewSendDate = $reviewConfigurationService->getSendDate($reviewConfiguration, $visit);

                $reviewService->sendEmail("survey_vue", [], $reviewSendDate, $user->id, $brand->id);
                $this->satisfactionService->setSurveyReviewSent($satisfactionId, $userSurvey);

                Log::info("SAVE SURVEY ANSWER INFO: Review email event dispatched", ["brand" => $this->data->brandId, "data" => $this->data->getAttributes()]);
            }
        }
    }

    private function sendThanksEmail($survey, $surveyConfiguration, $surveyAnswer, $brand, $user)
    {
        if ($surveyConfiguration->sendThanksMail) {
            $satisfactionThanksEmailService = new SatisfactionEmailThanksService($this->gatewayConnection);
            $sendDate = Carbon::now()->toIso8601ZuluString();
            $satisfactionThanksEmailService->sendEmail(
                "survey_vue",
                [],
                $sendDate,
                $user->id,
                $brand->id,
                ($surveyConfiguration->puntMin <= $surveyAnswer->score)
            );

            Log::info("SAVE SURVEY ANSWER INFO: Thanks email event dispatched", ["brand" => $this->data->brandId, "data" => $this->data->getAttributes()]);
        }
    }

    private function getSurveysAnswer($score, $comment)
    {
        $surveyAnswer = [
            "score" => $score,
            "comment" => $comment
        ];
        return (object) $surveyAnswer;
    }
}
