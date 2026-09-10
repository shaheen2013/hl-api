<?php

namespace App\Exceptions\OfferWifi;

use Exception;
use Illuminate\Support\Facades\Log;

class OfferWifiInvalidRequestException extends Exception
{
    const DEFAULT_MESSAGE = 'Bad request body';
    protected $message = "";
    protected $detail = [];

    public function __construct($message = "", $detail = [], $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = empty($message) ? self::DEFAULT_MESSAGE : $message;
        $this->detail = $detail;
    }

    public function report()
    {
        Log::error($this->message, $this->detail);
    }
}
