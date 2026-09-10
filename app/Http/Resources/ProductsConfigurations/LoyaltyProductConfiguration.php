<?php

namespace App\Http\Resources\ProductsConfigurations;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 */
class LoyaltyProductConfiguration extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $configuration = [];
        if ($this->loyaltyConfig) {
            $configuration = [
                'brandID' => $this->id,
                'summaryActive' => $this->loyaltyConfig->summary_active,
                'summarySendDays' => $this->loyaltyConfig->summary_send_days,
                'summarySendHours' => $this->loyaltyConfig->summary_send_hours,
            ];
        }
        return $configuration;
    }
}
