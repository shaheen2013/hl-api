<?php

namespace App\Events;

use Hotelinking\Services\HLConnectionInterface;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class HLEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $payload;
    public $integration;
    public $origin;

    protected $originalEntity;
    protected $eventID;
    protected $context;



    /**
     * Create a new event instance.
     *
     * @param $payload
     * @param $connection
     */
    public function __construct($payload, HLConnectionInterface $connection)
    {
        $this->payload = $payload;
        $this->integration = $connection;
        $this->origin = explode('/', Arr::get($this->payload, 'origin'));

        $this->originalEntity = Arr::get($payload, "originalEntity");
        $this->context = Arr::get($payload, "context");
        $this->eventID = Arr::get($payload, "eventID");
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function getOriginalEntity($defaultEntity)
    {
        return $this->originalEntity ? $this->originalEntity : $defaultEntity;
    }

    public function addContext($key, $element)
    {
        $this->context[$key] = $element;
    }

    public function getEventID()
    {
        return $this->eventID;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function addElementToPayload($key, $element)
    {
        $this->payload['payload'][$key] = $element;
    }

    public function getPayload()
    {
        return $this->payload['payload'];
    }
}
