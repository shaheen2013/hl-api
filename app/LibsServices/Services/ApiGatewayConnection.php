<?php

namespace Hotelinking\Services;

class ApiGatewayConnection implements HLConnectionInterface
{
    private $schemaValidator;

    public function __construct($schemaValidator = null)
    {
        $this->schemaValidator = $schemaValidator;
    }

    public function sendRequest($payload, $path, $method = 'POST', $headers = [])
    {
        return json_encode(['success' => true]);
    }
}
