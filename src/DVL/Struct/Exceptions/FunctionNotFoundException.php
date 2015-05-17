<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of FunctionNotFoundException
 *
 * @author User
 */
class FunctionNotFoundException extends Exception {
    
    private $functionName;
    
    function __construct($functionName) {
        $this->functionName = $functionName;
    }
    
}