<?php

namespace DVL\Struct\Exceptions;

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
