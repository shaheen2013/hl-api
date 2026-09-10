<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 26/07/2019
 * Time: 13:17
 */

namespace App\Listeners;

use App\Events\UserConnected;
use App\Events\BrandVisited;
use App\Services\Emails\ReviewEmailService;
use Illuminate\Support\Facades\Cache;

class VisitListener
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
            'App\Events\UserConnected',
            'App\Listeners\VisitListener@registerVisit'
        );

        $event->listen(
            'App\Events\BrandVisited',
            'App\Listeners\VisitListener@flushClientsCache'
        );

        $event->listen(
            'App\Events\UserConnected',
            'App\Listeners\VisitListener@flushBrandUserCache'
        );
    }

    /**
     * @param UserConnected $event
     * @throws \Exception
     */
    public function registerVisit(UserConnected $event)
    {
        $userBrand = $event->getUserBrand();
        $user = $event->getUser();
        $visit = $event->getVisit();
        $stay = $event->getStay();
        $userAgent = $event->getUserAgent();
        $connection = $event->getConnection();
        $reservation = $event->getReservation();

        if ($visit) {
            $event->updateVisit($visit, $stay, $reservation);
        } else {
            $visit = $event->storeVisit($userBrand, $stay, $reservation);

            $reviewConfigurationService = app('App\Services\Products\ReviewProductService');
            $reviewConfiguration = (object) $reviewConfigurationService->getConfiguration($userBrand->brand_id)->toArray(null);

            if (data_get($reviewConfiguration, "active") && $reviewConfiguration->ignore_rating) {
                $reviewService = new ReviewEmailService($event->integration);
                $reviewSendDate = $reviewConfigurationService->getSendDate($reviewConfiguration, $visit);
               
                $reviewService->sendEmail("survey_vue", [], $reviewSendDate, $user->id, $userBrand->brand_id);
            }
        }

        $event->storeConnection($user, $visit, $stay, $userAgent, $connection);
    }

    /**
     * @param BrandVisited $event
     * @throws \Exception
     */
    public function flushClientsCache(BrandVisited $event)
    {
        $brand = json_decode($event->getBrand());

        Cache::tags(['clients_' . $brand->id])->flush();

        if ($brand->parent_id) {
            Cache::tags(['clients_' . $brand->parent_id])->flush();
        }
    }

    /**
     * Clear cache by flushing a tag for a brand that has a change user connected
     *
     * @param UserConnected $event
     *
     * @throws \Exception
     */
    public function flushBrandUserCache(UserConnected $event)
    {
        $brand = json_decode($event->getBrand());
        $user = $event->getUser();

        Cache::tags(["brandUsers-{$brand->id}"])->flush();
        Cache::tags(["user-{$user->id}"])->flush();
    }
}
