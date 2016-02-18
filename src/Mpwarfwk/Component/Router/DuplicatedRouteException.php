<?php

namespace Mpwarfwk\Component\Router;

use Exception;

class DuplicatedRouteException extends Exception
{
    const EXCEPTION_CODE = 2;

    public function __construct($message, Exception $previous = null) {
        parent::__construct($message, self::EXCEPTION_CODE, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}