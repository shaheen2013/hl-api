<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Hotelinking\Services\EventBridge\EventBridgeEvent;
use Illuminate\Support\Facades\Log;

class UserCreatedEvent extends EventBridgeEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /** @var array */
    public $payload;

    protected $brand_id;
    private $user;
    private $user_id;
    private $birthday;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
        $this->brand = $payload['brand'];
        $this->user = $payload['user'];

        $this->userService = app('App\Services\Users\UserService');
        $this->brandService = app('App\Services\BrandService');

        // Trigger parent constuctor to set event variables
        parent::__construct("user_created_event");
    }

    /**
     * Construct event detail to be send to eventBridge
     *
     * @return array
     */
    public function mountDetail(): array
    {
        Log::info('UserCreatedEvent', ['message' => 'Sending UserCreatedEvent', 'brand' => $this->brand, 'user' => $this->user]);
        // Get all event data
        return [
            "brand" => [
                "id" => $this->brand['id']
            ],
            "user" => [
                "id" => $this->user['id'],
                "first_name" => $this->user['first_name'],
                "last_name" => $this->user['last_name'],
                "email" => $this->user['email'],
                "gender" => $this->user['gender'],
                "lang" => $this->user['lang'],
                "locale" => $this->user['locale'],
                "country" => $this->user['country'],
                "document_number" => $this->user['document_number'],
                "sendex" => (float) $this->user['sendex'],
                "email_result" => $this->user['email_result'],
                "birthday" => $this->user['birthday'],
                "unsubscribed" => $this->user['unsubscribed'],
                "customer" => $this->user['customer'],
                "pms_id" => $this->user['pms_id'],
                "origin" => $this->user['origin'],
                "facebook_id" => $this->user['facebook_id'] ?? null,
                "facebook_img" => $this->user['facebook_img'] ?? null,
                "facebook_friends" => $this->user['facebook_friends'] ?? null
            ]
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
