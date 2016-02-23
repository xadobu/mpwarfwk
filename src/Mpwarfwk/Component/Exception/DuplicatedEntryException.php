<?php

namespace Mpwarfwk\Component\Exception;

use Exception;

class DuplicatedEntryException extends Exception
{
    const EXCEPTION_CODE = 2;

    public function __construct($message, Exception $previous = null) {
        parent::__construct($message, self::EXCEPTION_CODE, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}