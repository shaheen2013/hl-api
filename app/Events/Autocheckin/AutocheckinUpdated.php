<?php

namespace App\Events\Autocheckin;

use Hotelinking\Services\EventBridge\EventBridgeEvent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\Log;

class AutocheckinUpdated extends EventBridgeEvent
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

        // Trigger parent constructor to set event variables
        parent::__construct("autocheckin_updated");
    }

    /**
     * Construct event detail to be send to eventBridge
     *
     * @return array
     */
    public function mountDetail(): array
    {
        Log::info('AutocheckinUpdated', ['message' => 'Sending AutocheckinUpdated event', 'brand' => $this->payload['brand'], 'product' => $this->payload['product'], 'config' => $this->payload['config']]);

        return [
            "brand" => [
                "id" => $this->payload['brand']
            ],
            "product" => [
                "id" => $this->payload['product']
            ],
            "config" => data_get($this->payload, "config.data")
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
