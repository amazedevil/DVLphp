<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Exceptions;

/**
 * Description of TypeException
 *
 * @author User
 */
class TypeException extends BaseValidationException {
        
    public $expectedType;
    public $value;
    public $type;
    
    function __construct($value, $type, $expectedType, $message = null) {
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
