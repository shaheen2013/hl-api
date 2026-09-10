<?php

namespace App\Exceptions\ProductConfig;

use Exception;
use Throwable;
use Illuminate\Support\Facades\Log;

class ProductConfigNotFound extends Exception
{
    const DEFAULT_MESSAGE = 'Product config not found';
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
