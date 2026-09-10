<?php

namespace App\Exceptions\gdpr;

use Exception;
use Illuminate\Support\Facades\Log;

class ChainGdprUnavailableException extends Exception
{
    const DEFAULT_MESSAGE = 'The brand is a chain, no gdpr available for chains.';
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

    /**
     * Define status code for response
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return 400;
    }

    /**
     * Message to use on response when have a custom message for a external request
     *
     * @return string
     */
    public function getExternalMessage(): string
    {
        return $this->message;
    }
}
