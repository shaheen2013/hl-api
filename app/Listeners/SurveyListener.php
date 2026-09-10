<?php

namespace App\Listeners;

use App\Services\Emails\ReviewEmailService;
use App\Services\Emails\SatisfactionEmailThanksService;
use App\Services\Emails\SatisfactionWarningEmailService;
use App\Services\Products\ProductService;
use App\Services\BrandService;
use App\Services\BrandProductService;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class SurveyListener
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Register the listeners for the subscriber.
     * @param $event
     */
    public function subscribe($event)
    {
        $event->listen(
            'App\Events\SatisfactionSurveyAnswered',
            'App\Listeners\SurveyListener@sendReviewEmail'
        );

        $event->listen(
            'App\Events\SatisfactionSurveyAnswered',
            'App\Listeners\SurveyListener@sendWarningEmail'
        );

        $event->listen(
            'App\Events\SatisfactionSurveyAnswered',
            'App\Listeners\SurveyListener@sendThanksEmail'
        );

        $event->listen(
            'App\Events\SatisfactionSurveyAnswered',
            'App\Listeners\SurveyListener@saveSurveyInfo'
        );

        $event->listen(
            'App\Events\BrandVisited',
            'App\Listeners\SurveyListener@sendCustomizedSatisfaction'
        );
    }

    public function sendReviewEmail($event)
    {
        $surveyConfiguration = $event->getBrandSatisfactionConfiguration();
        $reviewConfiguration = $event->getBrandReviewConfiguration();
        $surveyAnswer = $event->getSurveysAnswer();
        $survey = $event->getSurvey();

        if (!$survey->done) {
            if ($reviewConfiguration->active && $surveyConfiguration->reviewAverageScore <= $surveyAnswer->score) {
                $reviewService = new ReviewEmailService($event->integration);
                $reviewSendDate = Carbon::now()->addDays($reviewConfiguration->send_after_days)->toIso8601ZuluString();
                $reviewService->sendEmail($event->getOriginalEntity('hotelinking_app'), [], $reviewSendDate, $event->user_id, $event->brand_id);
                $event->setReviewSent($event->satisfaction_id);
            }
        }
    }

    public function saveSurveyInfo($event)
    {
        $surveyConfiguration = $event->getBrandSatisfactionConfiguration();
        $surveyAnswer = $event->getSurveysAnswer();
        $survey = $event->getSurvey();

        if (!$survey->done) {
            $event->updateSatisfactionSurvey($event->satisfaction_id, $surveyAnswer->score, $surveyAnswer->comment);
        }
    }

    public function sendWarningEmail($event)
    {
        $surveyConfiguration = $event->getBrandSatisfactionConfiguration();
        $surveyAnswer = $event->getSurveysAnswer();
        $survey = $event->getSurvey();

        if (!$survey->done) {
            if (($surveyConfiguration->puntMin > $surveyAnswer->score || !$surveyConfiguration->filterWarning) && $surveyConfiguration->warningEmail) {
                $sent = Carbon::parse($survey->fecha_creado)->addDays($surveyConfiguration->sendAfterDays)->addHours($surveyConfiguration->sendHour);

                $warningService = new SatisfactionWarningEmailService($event->integration);
                $warningService->sendEmail(
                    $event->getOriginalEntity('hotelinking_app'),
                    [],
                    $sent,
                    $event->getUser(),
                    $event->getBrand(),
                    $survey,
                    $surveyConfiguration->puntMin,
                    $surveyConfiguration->warningEmail,
                    $surveyConfiguration->customizedActive,
                    $surveyAnswer->score,
                    $surveyAnswer->comment,
                    $surveyConfiguration->customizedType,
                    []
                );
            }
        }
    }

    public function sendThanksEmail($event)
    {
        $surveyConfiguration = $event->getBrandSatisfactionConfiguration();
        $surveyAnswer = $event->getSurveysAnswer();
        $survey = $event->getSurvey();

        if ($surveyConfiguration->sendThanksMail) {
            $satisfactionThanksEmailService = new SatisfactionEmailThanksService($event->integration);
            $sendDate = Carbon::now()->toIso8601ZuluString();
            $satisfactionThanksEmailService->sendEmail(
                $event->getOriginalEntity('hotelinking_app'),
                [],
                $sendDate,
                $event->user_id,
                $event->brand_id,
                ($surveyConfiguration->puntMin <= $surveyAnswer->score)
            );
        }
    }

    public function sendCustomizedSatisfaction($event)
    {
        $brand = json_decode($event->getBrand());
        $user = json_decode($event->getUser());

        $brandService = app('App\Services\BrandService');
        $brandProductService = app('App\Services\BrandProductService');
        $satisfactionConfiguration = $brandService->getBrandSatisfactionConfiguration($brand);

        $customizedSatisfactionProduct = $this->productService->getByName('customized_satisfaction_surveys');
        $brandcustomizedSatisfactionProduct = $brandProductService->get($brand->id, $customizedSatisfactionProduct->id);

        if ($satisfactionConfiguration &&
            ($satisfactionConfiguration->customized_type == 'In a later email' || $satisfactionConfiguration->customized_type == 'After PMS checkout date') &&
            $satisfactionConfiguration->customized_active &&
            $brandcustomizedSatisfactionProduct &&
            $brandcustomizedSatisfactionProduct->active
        ) {
            $satisfactionService = app('App\Services\Products\SatisfactionSurveyProductService');
            $satisfactionUrl = $satisfactionService->createSatisfactionUrl($user, $brand);
            $userSatisfaction = $satisfactionService->getLastUserSatisfaction($user->id, $brand->hotel_id);

            $brandTreatment = Arr::first($brand->brand_protocol, function ($value, $key) {
                return $value->service === 'emails';
            });

            if ($userSatisfaction && $userSatisfaction->customized_send_date > $userSatisfaction->send_date && $satisfactionUrl) {
                $event->addContext('previousEventID', $event->getEventID());

                $sendCustomizedSatisfactionEmail = [
                    "schema"         => "com.hotelinking/Emails/send_customized_satisfaction_email/1.0.0",
                    "origin"         => "HotelinkingApi/" . $brand->id,
                    "originalEntity" => $event->getOriginalEntity("hotelinking"),
                    "eventSource"    => url()->current(),
                    "context"        => $event->getContext(),
                    "payload"        => [
                        "satisfactionUrl" => $satisfactionUrl,
                        "sendDate" => Carbon::parse($userSatisfaction->customized_send_date)->toIso8601ZuluString(),
                        "user" => [
                            "id"  => $user->id,
                            "name"  => $user->name,
                            "guid"  => $user->user_guid->guid,
                            "email"  => $user->email,
                            "lang"   => $user->lang,
                            "gender" => $user->gender,
                        ],
                        "brand"           => [
                            "id"         => $brand->id,
                            "hotelID"    => $brand->hotel_id,
                            "hotelGuid"  => $brand->brandInfo->hotel_guid->guid,
                            "chainID"    => $brand->chain_id,
                            "name"       => $brand->brandInfo->name,
                            "email"      => $brand->brandInfo->sending_email ? $brand->brandInfo->sending_email : 'noreply@hotelinking.com',
                            "treatment"  => data_get($brandTreatment, 'treatment', 'formal'),
                            "brandImage" => empty($brand->brandInfo->logo) ? null : $brand->brandInfo->logo,
                        ]
                    ]
                ];

                $event->integration->sendRequest($sendCustomizedSatisfactionEmail, null);
            }
        }
    }
}
