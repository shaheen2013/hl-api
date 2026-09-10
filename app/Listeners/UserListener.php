<?php

namespace App\Listeners;

use App\GdprHistory;
use App\UserFacebook;
use App\UserHotel;
use App\HotelSatisfaction;
use App\UserGuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;

class UserListener
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
     *
     * @param  \Illuminate\Events\Dispatcher  $event
     */
    public function subscribe($event)
    {
        $event->listen(
            'App\Events\UserCreated',
            'App\Listeners\UserListener@saveGeneration'
        );

        $event->listen(
            'App\Events\CreateUser',
            'App\Listeners\UserListener@createUser'
        );

        $event->listen(
            'App\Events\GdprUserAccepted',
            'App\Listeners\UserListener@gdprUserAccepted'
        );

        $event->listen(
            'App\Events\UserUnsubscribed',
            'App\Listeners\UserListener@userUnsubscribed'
        );

        $event->listen(
            'App\Events\SocialMediaShare',
            'App\Listeners\UserListener@saveSocialMediaShare'
        );
    }


    public function userUnsubscribed($event)
    {
        $userID = $event->getUserID();
        $brand = json_decode($event->getBrand());

        if ($userID) {
            $event->unsubscribe($userID, $brand);
        }
    }
    /**
     * @param $event
     * @throws \Exception
     */
    public function gdprUserAccepted($event)
    {

        $brand = json_decode($event->getBrand());
        $userID = $event->getUserID();

        if ($brand->brand_type->name == 'Hotel') {
            $gdpr = new GdprHistory();
            $gdpr->hotel_id = $brand->brandInfo->id;
            $gdpr->user_id = $userID;
            $gdpr->event = 'conditions';
            $gdpr->created_at = now();
            $gdpr->save();

            $gdpr = new GdprHistory();
            $gdpr->hotel_id = $brand->brandInfo->id;
            $gdpr->user_id = $userID;
            $gdpr->event = 'notifications';
            $gdpr->created_at = now();
            $gdpr->save();
        }
    }

    /**
     * @param $event
     * @throws \Exception
     */
    public function createUser($event)
    {
        $eventName = 'user_updated';
        $payloadUser = $event->getUser();

        try {
            $user = $event->getUserInfo();
        } catch (ModelNotFoundException $e) {
            $eventName = 'user_created';
            $user = $payloadUser;
            $user->save();
        }

        $guid = $event->createGuid();

        UserGuid::firstOrCreate(
            [
                "id_usuario" => $user->id
            ],
            [
                "guid" => $guid
            ]
        );

        if (Carbon::parse($user->created)->addDays(1) > Carbon::now()) {
            $eventName = 'user_created';
        }

        $userID = $user->id;

        // Create user facebook
        if ($event->getSource() == 'facebook') {
            UserFacebook::updateOrCreate(
                [
                    'id_usuario' => $userID,
                    'id_facebook' => $event->getFacebookID()
                ],
                [
                    'amigos' => $event->getFacebookFriends(),
                    'facebook_img' => $event->getFacebookImage(),
                    'email' => $payloadUser->email,
                    'nombre' => $payloadUser->name,
                    'gender' => $payloadUser->sexo,
                    'locale' => $payloadUser->location
                ]
            );
        }

        // Create GDPR history
        $brand = $event->getBrand();
        if (Arr::get($brand->brandInfo, 'cadena_hotel')) {
            $hotelsIDs = Arr::get($brand->brandInfo, 'cadena_hotel')->pluck('id_hotel');
        } else {
            $hotelsIDs = [Arr::get($brand->brandInfo, 'id')];
        }

        $gdprHistoryInfo = [];
        $gdprAcceptedTime = $event->getGdprAcceptedTime();
        foreach ($hotelsIDs as $hotelID) {
            array_push($gdprHistoryInfo, ["hotel_id" => $hotelID, "user_id" => $userID, "event" => "not_client", "created_at" => gmdate("Y-m-d H:i:s", strtotime($gdprAcceptedTime))]);
            array_push($gdprHistoryInfo, ["hotel_id" => $hotelID, "user_id" => $userID, "event" => "notifications", "created_at" => gmdate("Y-m-d H:i:s", strtotime($gdprAcceptedTime))]);
            array_push($gdprHistoryInfo, ["hotel_id" => $hotelID, "user_id" => $userID, "event" => "conditions", "created_at" => gmdate("Y-m-d H:i:s", strtotime($gdprAcceptedTime))]);
        }


        GdprHistory::insert($gdprHistoryInfo);

        // Response with event user_created
        try {
            $event->addElementToPayload('id', $userID);
        } catch (\Throwable $e) {
            $event->addElementToPayload('id', null);
        }

        $event->addContext('brandID', $event->getBrandID());
        $event->addContext('previousEventID', $event->getEventID());

        $widgetUid = explode('/', Arr::get($event->payload, 'origin'));
        $answer =  [
            "schema"         => "com.hotelinking/Users/{$eventName}/1.0.0",
            "origin"         => "HotelinkingApi/" . end($widgetUid),
            "originalEntity" => $event->getOriginalEntity("widget"),
            "eventSource"    => url()->current(),
            "context"        => $event->getContext(),
            "payload"        => $event->getPayload(),
        ];

        $event->integration->sendRequest($answer, null);
    }

    public function saveSocialMediaShare($event)
    {
        $event->save();
    }

    public function saveGeneration($event)
    {
        $userID = $event->getUserID();
        $birthdate = $event->getBirthdate();

        $generation = $event->getGeneration($birthdate);
        $event->update($userID, $generation);
    }
}
