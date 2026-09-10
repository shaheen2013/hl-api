<?php

namespace App\Exceptions\Clients;

use App\Exceptions\HLException;

class InvalidClientsRequestDataException extends HLException
{
    const DEFAULT_MESSAGE = 'Bad fields in request body';

    public function __construct($message = "", $detail = [], $code = 0, Throwable $previous = null)
    {
        $message = empty($message) ? self::DEFAULT_MESSAGE : $message;
        parent::__construct($message, $detail, $code, $previous);
    }
}
