<?php

namespace Hotelinking\Services\EventBridge;

class EventBridgeEvent
{
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
