<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of ArrayItemValidationException
 *
 * @author User
 */
class ArrayItemValidationException extends BaseValidationException {
    
    private $key;
    private $nested;
    
    function __construct($key, BaseValidationException $validationException) {
        parent::__construct($validationException->getMessage(), 0, $validationException);
        $this->key = $key;
        $this->nested = $validationException;
    }
    
    public function getKey() {
        return $this->key;
    }

    public function getNestedException() {
        return $this->nested;
    }
    
    public function getFinalException() {
        return $this->getNestedException()->getFinalException();
    }
    
    public function getMessageException() {
        return $this->getNestedException()->getMessageException();
    }

}
