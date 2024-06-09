<?php

namespace App\Exception;

use Exception;
use ReturnTypeWillChange;

class HasTransferException extends Exception
{
    public function __construct($message = "Has Transfer", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    #[ReturnTypeWillChange]
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}