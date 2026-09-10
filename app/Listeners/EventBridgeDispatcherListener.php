<?php

namespace App\Listeners;

use App\Events\Autocheckin\AutocheckinUpdated;
use App\Events\Brand\BrandUpdated;
use App\Events\Account\AccountUpdated;
use App\Events\Brand\BrandArchived;
use App\Events\UserUnsubscribedEvent;
use App\Events\UserCreatedEvent;
use App\Events\UserUpdatedEvent;
use App\Events\Surveys\SurveyQuestionAnsweredEvent;
use Illuminate\Support\Facades\Log;

class EventBridgeDispatcherListener
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
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            [
                UserUnsubscribedEvent::class,
                UserCreatedEvent::class,
                UserUpdatedEvent::class,
                SurveyQuestionAnsweredEvent::class,
                BrandUpdated::class,
                BrandArchived::class,
                AccountUpdated::class,
                AutocheckinUpdated::class
            ],
            'App\Listeners\EventBridgeDispatcherListener@publishOnEventBridge'
        );
    }

    /**
     * Publish event on eventBridge
     *
     * @param object $event
     * @return void
     */
    public function publishOnEventBridge($event)
    {
        // Send event to eventBridge
        $response = $event->send();

        Log::debug("EventBridgeDispatcherListener", [
            'detail' => $event->getDetail(),
            'detailType' => $event->getDetailType(),
            'response' => $response
        ]);
    }
}
