<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Visits\VisitService;
use App\Repositories\Visits\UserBrandRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class UserSurveyQuestionAnswerExternal extends JsonResource
{

    protected $visitService;
    protected $userBrandRepository;

    // Constructor to accept VisitService and UserBrandRepository
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
        $brandId = $this->brand_id;
        $userId = $this->user_id;
        $createdAt = Carbon::parse($this->created_at)->addHour();
        $visit = null;

        try {
            $userBrand = $this->userBrandRepository->get($brandId, $userId);
            $relatedVisit = $this->visitService->getVisit($userBrand->id, $createdAt);

            if ($relatedVisit) {
                $reservationData = json_decode($relatedVisit->reservation);

                $resChannel = $reservationData->res_channel ?? null;
                $checkIn = $reservationData->check_in ?? null;
                $checkOut = $reservationData->check_out ?? null;

                $visit [] = [
                'checkIn' =>  $checkIn,
                'checkOut' => $checkOut,
                'resChannel' => $resChannel,
                ];
            } else {
                Log::debug("No related visit found.");
            }
        } catch (\Exception $e) {
            Log::debug("Error: " . $e->getMessage());
        }

        return [
            "id" => $this->id,
            "name" => $this->name,
            "country" => $this->country,
            "score" => $this->answer,
            "comment" => $this->comment,
            "lang" => $this->lang,
            "category" => $this->categoryText != 'N/A' ? $this->categoryText : null,
            "avatar" => $this->facebookImg ?? config('services.surveys.avatar-placeholder'),
            'brand' => [
                'id' => $this->brand_id,
                'name' => $this->hotel_name,
            ],
            "roomID" => !empty($this->roomID) ? $this->roomID : 'N/A',
            "created_at" => Carbon::parse($this->answered_at)->toDateTimeString(),
            "visit" => $visit
        ];
    }
}
