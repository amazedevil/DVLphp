<?php

namespace DVL\Struct\Exceptions;

class ValueValidationException extends BaseValidationException {
    
    private $value;
    
    function __construct($value) {
        $this->value = $value;
    }
    
    public function getInvalidValue() {
        return $this->value;
    }
    
}
