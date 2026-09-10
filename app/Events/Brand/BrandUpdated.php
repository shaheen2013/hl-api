<?php

namespace App\Events\Brand;

use Hotelinking\Services\EventBridge\EventBridgeEvent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\Log;

class BrandUpdated extends EventBridgeEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /** @var array */
    public $payload;

    private $brand;
    private $account;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
        $this->brand = $payload['brand'];
        $this->account = $payload['account'];


        // Trigger parent constructor to set event variables
        parent::__construct("brand_updated");
    }

    /**
     * Construct event detail to be send to eventBridge
     *
     * @return array
     */
    public function mountDetail(): array
    {
        Log::info('BrandUpdated', ['message' => 'Sending BrandUpdated event', 'brand' => $this->brand]);

        $details =  [
            "brand" => [
                "id" => $this->brand->id,
                "name" => $this->brand->hotel->name,
                "logo" => $this->brand->hotel->logo,
                "email" => $this->brand->hotel->email,
                "background_color" => $this->brand->background_color,
                "background_image" => $this->brand->hotel->fotoBg,
                "place_country" => $this->brand->hotel->place_country,
                "time_zone" => $this->brand->hotel->timeZone->time_zone,
                "products" => $this->brand->hotel->brandProduct,
                "activated" => $this->brand->hotel->activated,
            ],
        ];

        if ($this->account) {
            $details['account'] = [
                "id"    => data_get($this->account, 'id'),
                "name"  => data_get($this->account, 'brandInfo.nombre'),
                "logo"  => data_get($this->account, 'brandInfo.logo'),
                "background_color" => data_get($this->account, 'background_color')
            ];
        }

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
