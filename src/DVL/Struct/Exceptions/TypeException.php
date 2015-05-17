<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of TypeException
 *
 * @author User
 */
class TypeException extends BaseValidationException {
        
    public $expectedType;
    public $type;
    
    function __construct($type, $expectedType, $message = null) {
        $this->type = $type;
        $this->expectedType = $expectedType;
        parent::__construct($message === null ? $this->getDefaultMessage() : $message);
    }
    
    private function getDefaultMessage() {
        return "Wrong type: {$this->type} expected: {$this->expectedType}";
    }
    
}
