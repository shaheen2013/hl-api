<?php

namespace App\Events\Surveys;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Hotelinking\Services\EventBridge\EventBridgeEvent;

class SurveyQuestionAnsweredEvent extends EventBridgeEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /** @var array */
    public $payload;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;

        // Trigger parent constuctor to set event variables
        parent::__construct("survey_question_answered");
    }

    /**
     * Construct event detail to be send to eventBridge
     *
     * @return array
     */
    public function mountDetail(): array
    {
        return $this->payload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel(null);
    }
}
