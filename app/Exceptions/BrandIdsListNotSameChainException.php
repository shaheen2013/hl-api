<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class BrandIdsListNotSameChainException extends Exception
{
    const DEFAULT_MESSAGE = 'The brands on list do not belong to same chain.';
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
