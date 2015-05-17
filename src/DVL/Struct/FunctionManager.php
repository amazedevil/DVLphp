<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of FunctionManager
 *
 * @author User
 */
class FunctionManager {
    
    private $functions;
    
    function __construct() {
        $this->functions = [
            'KEYS' => function($arr) { return keys($arr); },
        ];
    }
    
    public function executeFunction($name, $args) {
        if (isset($this->functions[$name])) {
            call_user_func_array($this->functions[$name], $args);
        } else {
            throw new FunctionNotFoundException($name);
        }
    }
    
}
