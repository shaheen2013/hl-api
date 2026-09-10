<?php

namespace App\Http\Resources;

use App\Repositories\Visits\UserBrandRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\Visits\VisitService;

class SatisfactionSurvey extends JsonResource
{

    protected $visitService;
    protected $userBrandRepository;

    public function __construct($resource, VisitService $visitService, UserBrandRepository $userBrandRepository)
    {
        $this->visitService = $visitService;
        $this->userBrandRepository = $userBrandRepository;
        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $satisfactionAnswers = [];

        $groupedAnswers = $this->satisfactionAnswer->groupBy('survey_question_id');

        foreach ($groupedAnswers as $answers) {
            $category = [];
            $questions = [];
            $firstAnswer = $answers[0];
            if (isset($firstAnswer->question->category->categoryText) && isset($firstAnswer->question->questionText)) {
                foreach ($firstAnswer->question->category->categoryText as $categoryTexts) {
                    $category [$categoryTexts->lang_value] = $categoryTexts->text;
                }
                foreach ($firstAnswer->question->questionText as $question) {
                    $questions [$question->lang_value] = $question->text;
                }

                $response = $firstAnswer->answer;
                foreach ($answers as $answer) {
                    if ($answer->question_response_id) {
                        $response = isset($response['en']) ? $response : [];
                        foreach ($answer->questionResponse->questionResponseText as $questionResponse) {
                            $previousResponses = isset($response[$questionResponse->lang_value]) ? $response [$questionResponse->lang_value] . ", " : "";
                            $response [$questionResponse->lang_value] = $previousResponses . $questionResponse->text;
                        }
                    }
                }

                $satisfactionAnswers [] = [
                    'id' => $firstAnswer->survey_question_id,
                    'answer' => $response,
                    'comment' => $firstAnswer->comment,
                    'createdAt' => Carbon::createFromFormat('Y-m-d H:i:s', $firstAnswer->created_at, 'UTC')->setTimezone($this->hotel->timeZone->time_zone)->toDateTimeString(),
                    'surveyCategory' => $category,
                    'questions' => $questions
                ];
            }
        }

        $incidents = [];
        if ($this->userSurvey) {
            foreach ($this->userSurvey->incidents as $incident) {
                $incidents [] = [
                    'id'                => $incident->id,
                    'incident_text'     => $incident->incident_text,
                    'staff'             => [
                        'name'      => $incident->hotelStaff ? $incident->hotelStaff->nombre : null,
                        'deleted'   => $incident->hotelStaff ? $incident->hotelStaff->deleted : 0,
                    ],
                    'created_at'        => $incident->created_at,
                ];
            }
        }

        $visits = [];
        if ($this->userSurvey) {
            $userId = data_get($this, "userSurvey.user_id");
            $brandId = data_get($this, "userSurvey.brand_id");
            $createdAt = Carbon::parse($this->userSurvey->created_at)->addHour();
            
            try {
                $userBrand = $this->userBrandRepository->get($brandId, $userId);
            
                $relatedVisit = $this->visitService->getVisit($userBrand->id, $createdAt);
                if ($relatedVisit) {
                    $reservationData = json_decode($relatedVisit->reservation);
                    $resChannel = $reservationData ? $reservationData->res_channel : null;

                    $visits [] = [
                    'id' => $relatedVisit->id,
                    'checkIn' => $reservationData->check_in,
                    'checkOut' => $reservationData->check_out,
                    'resChannel' => $resChannel,
                    ];
                } else {
                    Log::debug("No related visit found.");
                }
            } catch (\Exception $e) {
                Log::debug("Error: " . $e->getMessage());
            }
        }

        return [
            'id' => $this->id,
            'userSurveyId' => data_get($this, "userSurvey.id"),
            'comment' => $this->comentario,
            'score' => $this->puntuacion,
            'sendDate' => Carbon::createFromFormat('Y-m-d H:i:s', $this->send_date, 'UTC')->setTimezone($this->hotel->timeZone->time_zone)->toDateTimeString(),
            'answered' => Carbon::createFromFormat('Y-m-d H:i:s', $this->fecha_update, 'UTC')->setTimezone($this->hotel->timeZone->time_zone)->toDateTimeString(),
            'customSendDate' => $this->customized_send_date,
            'hasBeenSeen' => $this->has_been_seen,
            'whoHasBeenSeen' => $this->who_has_been_seen,
            'incidents_reviewed' => data_get($this, "userSurvey.incidents_reviewed"),
            'roomID' => $this->id_room,
            'done' => $this->done,
            'favorite' => $this->favorite,
            'reviewSend' => $this->review_send,
            'user' => [
                'id' => $this->user->id,
                'email' => $this->user->email,
                'name' => $this->user->name,
                'lang' => $this->user->lang,
                'location' => $this->user->location,
                'brithday' => $this->user->birthday,
                'gender' => $this->user->gender,
                'country' => $this->user->country
            ],
            'hotel' => [
                'id' => $this->hotel->id,
                'name' => $this->hotel->hotelName,
                'brandID' => $this->hotel->brandId,
                'logo' => $this->hotel->logo
            ],
            'incidents' => $incidents,
            'satisfactionAnswers' => $satisfactionAnswers,
            'visits' => $visits
        ];
    }
}
