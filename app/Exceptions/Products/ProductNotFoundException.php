<?php

namespace App\Exceptions\Products;

use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductNotFoundException extends Exception
{
    const DEFAULT_MESSAGE = 'Product not found';
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

    public function getStatusCode(): int
    {
        return 404;
    }
}
