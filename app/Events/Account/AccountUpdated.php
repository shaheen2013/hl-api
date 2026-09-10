<?php

namespace App\Events\Account;

use Hotelinking\Services\EventBridge\EventBridgeEvent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\Log;

class AccountUpdated extends EventBridgeEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /** @var array */
    public $payload;

    private $account;
    private $brands;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
        $this->account = $payload['account'];
        $this->brands = $payload['brands'];


        // Trigger parent constructor to set event variables
        parent::__construct("account_updated");
    }

    /**
     * Construct event detail to be send to eventBridge
     *
     * @return array
     */
    public function mountDetail(): array
    {
        Log::info('AccountUpdated', ['message' => 'Sending AccountUpdated event', 'account' => $this->account]);

        $details = [
            "account" => [
                "id" => $this->account->id,
                "name" => $this->account->brandInfo->nombre,
                "logo" => $this->account->brandInfo->logo,
                "background_color" => $this->account->background_color,
            ],
            "brands" => $this->brands,
        ];

        return $details;
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
