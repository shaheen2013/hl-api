<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 07/05/2019
 * Time: 09:27
 */

namespace App\Listeners;

class StatsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Register the listeners for the subscriber.
     * @param $event
     */
    public function subscribe($event)
    {
        $event->listen(
            'App\Events\SatisfactionSurveyAnswered',
            'App\Listeners\StatsListener@registerSatisfactionSurvey'
        );
    }

    public function registerSatisfactionSurvey($event)
    {
        $user_id = $event->user_id;
        $brand = $event->getBrand();
        $brandParent = $brand->parent;
        $hotel_id = data_get($brand, 'hotel.id');
        $chain_id = data_get($brandParent, 'chain.id');
        $surveyAnswer = $event->getSurveysAnswer();
        $score = $surveyAnswer->score;
        $date = $this->formatDate();
        $time = $this->formatTime();
    }

    private function formatDate()
    {
        $year = date('Y');
        $month = sprintf("%02d", date('m'));
        $day = sprintf("%02d", date('d'));
        $date = $year . $month . $day;
        return $date;
    }

    private function formatTime()
    {
        $hour = sprintf("%02d", date('H'));
        $minute = sprintf("%02d", date('i'));
        $time = $hour . $minute;
        return $time;
    }
}
