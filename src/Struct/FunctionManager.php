<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct;

use DVL\Exceptions\NativeValidationFunctionException;

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
            'INT' => function($var) { return is_integer($var) ? $var : new NativeValidationFunctionException(NativeValidationFunctionException::FUNC_INT_TYPE_FAILED); }
        ];
    }
    
    public function executeFunction($name, array $args) {
        if (isset($this->functions[$name])) {
            return call_user_func_array($this->functions[$name], $args);
        } else {
            throw new FunctionNotFoundException($name);
        }
    }
    
}
