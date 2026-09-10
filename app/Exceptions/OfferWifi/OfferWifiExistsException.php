<?php

namespace App\Exceptions\OfferWifi;

use Exception;
use Illuminate\Support\Facades\Log;

class OfferWifiExistsException extends Exception
{
    const DEFAULT_MESSAGE = 'There is an equal offer within the selected date range';
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
        Log::warning($this->message, $this->detail);
    }
}
