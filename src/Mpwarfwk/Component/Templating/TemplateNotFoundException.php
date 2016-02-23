<?php

namespace Mpwarfwk\Component\Templating;

use Exception;

class TemplateNotFoundException extends Exception
{
    const EXCEPTION_CODE = 1;

    public function __construct($message, Exception $previous = null) {
        parent::__construct($message, self::EXCEPTION_CODE, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}