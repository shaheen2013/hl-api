<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class HLException extends Exception
{
    const DEFAULT_MESSAGE = '';
    const DEFAULT_EXCEPTION_CODE = 400;
    protected $message = "";
    protected $detail = [];
    protected $statusCode;

    public function __construct($message = "", $detail = [], $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $code ?: self::DEFAULT_EXCEPTION_CODE;
        $this->message = empty($message) ? self::DEFAULT_MESSAGE : $message;
        $this->detail = $detail;
    }

    public function report()
    {
        Log::warning($this->message, $this->detail);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
