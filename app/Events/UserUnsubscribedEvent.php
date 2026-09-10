<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Hotelinking\Services\EventBridge\EventBridgeEvent;
use Illuminate\Support\Facades\Log;

class UserUnsubscribedEvent extends EventBridgeEvent
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
        parent::__construct("user_unsubscribed");
    }

    /**
     * Construct event detail to be send to eventBridge
     *
     * @return array
     */
    public function mountDetail(): array
    {
        Log::info('UserUnsubscribedEvent', ['message' => 'Sending User unsubscribed event', 'brand' => $this->payload['brand'], 'user' => $this->payload['user']]);
        // Get all event data
        return [
            "message" => 'User Unsubscribed',
            "brand" => $this->payload['brand'],
            "user" => $this->payload['user']
        ];
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
