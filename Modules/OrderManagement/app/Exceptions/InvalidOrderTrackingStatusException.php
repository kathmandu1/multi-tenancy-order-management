<?php

namespace Modules\OrderManagement\Exceptions;

use Exception;
use Throwable;

class InvalidOrderTrackingStatusException extends Exception
{
    public function __construct(string $message = "Invalid order tracking status transition, please check order status properly.", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
