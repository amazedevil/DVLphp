<?php

namespace DVL\Struct\Exceptions;

use Exception;

abstract class BaseValidationException extends Exception {

    public function getFinalException() {
        return $this;
    }
    
    public function getMessageException() {
        return null;
    }
    
    public function getInvalidValue() {
        return null;
    }
    
}
