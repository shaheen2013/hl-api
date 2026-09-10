<?php

namespace App\Http\Resources\Stats;

use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyRecurrentStat extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $total = empty($this->total) ? 1 : $this->total;
        $relative = round($this->recurrents * 100 / $total, 2);

        return [
            'total'      => $this->total,
            'recurrents' => $this->recurrents,
            'relative'   => $relative
        ];
    }
}
