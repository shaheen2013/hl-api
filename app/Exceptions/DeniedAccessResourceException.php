<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;

class DeniedAccessResourceException extends HLException
{
    const DEFAULT_MESSAGE = 'Denied access to Resource to the current Brand';
    protected $message = "";
    protected $detail = [];

    public function __construct($message = "", $detail = [], $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $detail, $code, $previous);
        $this->message = empty($message) ? self::DEFAULT_MESSAGE : $message;
        $this->detail = $detail;
    }

    public function report()
    {
        Log::error($this->message, $this->detail);
    }
}
