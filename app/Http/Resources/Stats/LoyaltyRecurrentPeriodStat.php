<?php

namespace App\Http\Resources\Stats;

use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyRecurrentPeriodStat extends JsonResource
{
    /**
     * Given a date range, it returns a structure with the number of recurring users for the selected period.
     * It returns, therefore, the number of recurring visitors, the day, the month and the year.
     * Depending on the selected period the structure is grouped.
     * The question is that if there are no recurring users within the range, it must return 0,
     * therefore it does not depend on the result obtained in the database;
     * simply generate a structure given the range and fill in the information if in that period or group of dates there are recurring visitors.
     * @param array $resource
     * @param object $dates
     * @param string $index
     * @param string $increments
     */

    public function __construct(array $resource, object $dates, string $index, string $increments)
    {
        $visits = [];
        $loop_index = str_replace('-', '', $index);

        while ($dates->from->format($loop_index) <= $dates->to->format($loop_index)) {
            $current_date = $dates->from->format($index);
            $visits[] = [
                'recurrents' => $resource[$current_date] ?? 0,
                'day'        => $dates->from->format('Y-m-d'),
                'month'      => $dates->from->format('Y-m'),
                'year'       => $dates->from->format('Y')
            ];

            call_user_func([$dates->from, $increments], 1);
        }

        parent::__construct($visits);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
