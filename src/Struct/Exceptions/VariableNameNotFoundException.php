<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Exceptions;

/**
 * Description of VariableNameNotFoundException
 *
 * @author User
 */
class VariableNameNotFoundException extends BaseValidatorStructureException {
    
    private $name;
    
    function __construct($name) {
        $this->name = $name;
    }
    
}
