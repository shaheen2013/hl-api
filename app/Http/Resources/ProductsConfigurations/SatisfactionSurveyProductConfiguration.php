<?php

namespace App\Http\Resources\ProductsConfigurations;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed hotel
 */
class SatisfactionSurveyProductConfiguration extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $hotel = $this->hotel;
        $configuration = null;
        if ($hotel) {
            return [
                'brandID'            => $this->id,
                'warningEmail'       => $hotel->hotelSatisfaction->warning_email ?? null,
                'sendHour'           => $hotel->hotelSatisfaction->send_hour ?? null,
                'totalFollowupEmail' => $hotel->hotelSatisfaction->total_followup_email ?? null,
                'puntMin'            => $hotel->hotelSatisfaction->puntMin ?? null,
                'ignoreRating'       => $hotel->hotelSatisfaction->ignoreRating ?? null,
                'sendThanksMail'     => $hotel->hotelSatisfaction->sendThanksMail ?? null,
                'sendToNonCustomers' => $hotel->hotelSatisfaction->sendToNonCustomers ?? null,
                'sendAfterDays'      => $hotel->hotelSatisfaction->send_days ?? null,
                'filterWarning'      => $hotel->hotelSatisfaction->filter_warning ?? null,
                'forceComment'       => $hotel->hotelSatisfaction->force_comment ?? null,
                "customizedActive"   => $hotel->hotelSatisfaction->customized_active ?? 0,
                "customizedWarning"  => $hotel->hotelSatisfaction->customized_warning_emails ?? 0,
                "customizedType"     => $hotel->hotelSatisfaction->customized_type ?? 'Joined with satisfaction',
                "reviewAverageScore" => $hotel->hotelSatisfaction->review_average_score ?? null,
                "defaultScore"       => $hotel->hotelSatisfaction->default_score ?? null,
            ];
        }
    }
}
