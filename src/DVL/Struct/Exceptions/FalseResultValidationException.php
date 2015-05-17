<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of FalseResultValidationException
 *
 * @author User
 */
class FalseResultValidationException extends BaseValidationException {
    
    const OPERATION_GREATER = 1;
    const OPERATION_LESS = 2;
    const OPERATION_GREATER_OR_EQUAL = 3;
    const OPERATION_LESS_OR_EQUAL = 4;
    const OPERATION_EQUAL = 5;
    const OPERATION_NOT_EQUAL = 6;
    
    public $type;
    
    function __construct($type) {
        $this->type = $type;
    }
    
}
