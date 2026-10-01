<?php

namespace Hotelinking\Services;

interface HLConnectionInterface
{
    public function sendRequest($payload, $path, $method, $headers = []);
}
