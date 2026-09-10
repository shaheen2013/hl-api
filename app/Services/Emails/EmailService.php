<?php

/**
 * Created by PhpStorm.
 * User: hl
 * Date: 04/04/2019
 * Time: 16:17
 */

namespace App\Services\Emails;

use Exception;
use Illuminate\Support\Facades\Log;
use Hotelinking\Services\HLConnectionInterface;

class EmailService implements HLEmailService
{
    private $connection;

    public function __construct(HLConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $payload
     * @return mixed
     */
    public function send(array $payload)
    {
        try {
            $this->connection->sendRequest($payload, null);
            return true;
        } catch (Exception $e) {
            Log::error("invalid Email Params", [$e, $payload]);
            return false;
        }
    }
}
