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
    
    private $keys;
    
    function __construct($keys, BaseValidationException $validationException) {
        parent::__construct($validationException->getMessage(), 0, $validationException);
        $this->key = $keys;
    }
    
    public function getKeys() {
        return $this->keys;
    }
    
    public function addKeys( $keys ) {
        $this->keys[] = $keys;
    }
    
}
