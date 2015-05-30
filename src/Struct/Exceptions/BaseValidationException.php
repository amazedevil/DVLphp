<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Exceptions;

use Exception;

/**
 * Description of BaseValidationException
 *
 * @author User
 */
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
