<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of ValidationException
 *
 * @author User
 */
class ValidationException extends BaseValidationException {
    
    const EXCEPTION_CODE = 0;
    
    private $reasonException;
    
    function __construct(BaseValidationException $reasonException, $message) {
        $this->reasonException = $reasonException;
        parent::__construct($message, static::EXCEPTION_CODE, $reasonException);
    }
    
    public function getMessageException() {
        return $this;
    }
    
}
