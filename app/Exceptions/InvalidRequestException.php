<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class InvalidRequestException extends Exception
{
    const DEFAULT_MESSAGE = 'Invalid request: [{{ERROR_LIST}}]';
    protected $message = "";
    protected $detail = [];

    public function __construct($message = "", $detail = [], $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        // Set message variable
        $message = empty($message) ? self::DEFAULT_MESSAGE : $message;
        // Replace validation errors on message ERROR_LIST placeholder
        $detailsErrors = Arr::get($detail, 'errors', []);
        $message = str_replace('{{ERROR_LIST}}', implode(", ", $detailsErrors), $message);
        $this->message = $message;
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

    public function getDetail(): array
    {
        return $this->detail;
    }
}
