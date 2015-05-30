<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Exceptions;

/**
 * Description of ValidationException
 *
 * @author User
 */
class ValidationException extends BaseValidationException {
    
    const EXCEPTION_CODE = 0;
    
    private $reasonException;
    private $tag;
    
    function __construct(BaseValidationException $reasonException, $message, $tag = null) {
        $this->reasonException = $reasonException;
        $this->tag = $tag;
        parent::__construct($message, static::EXCEPTION_CODE, $reasonException);
    }
    
    public function getMessageException() {
        return $this;
    }
    
    public function getTag() {
        return $this->tag;
    }
    
}
