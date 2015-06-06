<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct;

use DVL\Struct\Exceptions\NativeValidationFunctionException;
use DVL\DVLParser;
use DVL\Struct\Exceptions\FunctionNameInvalidException;
use DVL\Struct\Exceptions\FunctionNotFoundException;
use InvalidArgumentException;

/**
 * Description of FunctionManager
 *
 * @author User
 */
class FunctionManager {
    
    private $functions;
    
    function __construct() {
        $this->functions = [
            'KEYS' => function($arr) { return array_keys($arr); },
            'INT' => function($var) { 
                return is_integer($var) ? 
                    $var : 
                    new NativeValidationFunctionException(
                        NativeValidationFunctionException::FUNC_INT_TYPE_FAILED
                    );
            },
            'STRLEN' => function($var) { return strlen($var); },
            'IS_ASSOC' => function($var) { 
                return is_array($var) && 
                    array_keys($var) !== range(0, count($var) - 1);
            },
            'IS_ARRAY' => function() {
                return is_array($var) && 
                    array_keys($var) === range(0, count($var) - 1);
            },
            'NATIVE_REGEX_MATCH' => function($var, $regex) { 
                return preg_match($regex, $var);
            }
        ];
    }
    
    private function isValidFunctionName($name) {
        $parser = new DVLParser($name);
        return $parser->match_Name() !== false;
    }
    
    public function addFunction($name, $function) {
        if ($this->isValidFunctionName($name)) {
            if (is_callable($function)) {
                $this->functions[$name] = $function;
            } else if (is_string($function)) {
                if (function_exists($function)) {
                    $this->functions[$name] = function() use ($function) { 
                        return $function();
                    };
                } else {
                    throw new FunctionNotFoundException();
                }
            } else {
                throw new InvalidArgumentException();
            }
        } else {
            throw new FunctionNameInvalidException();
        }
    }
    
    public function removeFunction($name) {
        unset($this->functions[$name]);
    }
    
    public function executeFunction($name, array $args) {
        if (isset($this->functions[$name])) {
            return call_user_func_array($this->functions[$name], $args);
        } else {
            throw new FunctionNotFoundException($name);
        }
    }
    
}
