<?php

namespace App\Http\Resources\ProductsConfigurations;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed hotel
 * @property mixed id
 */
class CustomizedSurveyProductConfiguration extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $hotel = $this->hotel;
        $configuration = [];
        if ($hotel && $hotel->hotelSatisfaction) {
            $configuration = [
                'brandID'                  => $this->id,
                'warningEmail'             => $hotel->hotelSatisfaction->warning_email ?? null,
                'minimumScore'             => $hotel->hotelSatisfaction->puntMin,
                'sendAfterDays'            => $hotel->hotelSatisfaction->send_days ?? null,
                'sendHour'                 => $hotel->hotelSatisfaction->send_hour ?? null,
                'filterWarning'            => $hotel->hotelSatisfaction->filter_warning ?? null,
                'customizedActive'         => $hotel->hotelSatisfaction->customized_active,
                'customizedType'           => $hotel->hotelSatisfaction->customized_type,
                'customizedSendDays'       => $hotel->hotelSatisfaction->customized_send_days,
                'customizedSendHours'      => $hotel->hotelSatisfaction->customized_send_hours,
                'customizedChainActivated' => $hotel->hotelSatisfaction->customized_chain_activated,
                'customizedComment'        => $hotel->hotelSatisfaction->customized_comment,
                'customizedWarning'        => $hotel->hotelSatisfaction->customized_warning_emails,
                'defaultScore'             => $hotel->hotelSatisfaction->default_score
            ];
        }
        return $configuration;
    }
}
