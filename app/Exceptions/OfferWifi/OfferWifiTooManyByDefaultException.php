<?php

namespace App\Exceptions\OfferWifi;

use Exception;
use Illuminate\Support\Facades\Log;

class OfferWifiTooManyByDefaultException extends Exception
{
    const DEFAULT_MESSAGE = 'There can only be one default offer by accommodated or non accommodated type';
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
