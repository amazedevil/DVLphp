<?php

namespace DVL\Struct\Exceptions;

use DVL\Struct\Exceptions\BaseValidationException;

class TypeException extends BaseValidationException {
        
    public $expectedType;
    public $value;
    public $type;
    
    function __construct($type, $expectedType, $value = null, $message = null) {
        $this->value = $value;
        $this->type = $type;
        $this->expectedType = $expectedType;
        parent::__construct($message === null ? $this->getDefaultMessage() : $message);
    }
    
    public function getInvalidValue() {
        return $this->value;
    }
    
    private function getDefaultMessage() {
        return "Wrong type: {$this->type} expected: {$this->expectedType}";
    }

}
