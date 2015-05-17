<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of FunctionExpression
 *
 * @author User
 */
class FunctionExpression extends BaseExpression {
    
    private $name;
    private $arguments;
    
    function __construct($name, array $arguments = []) {
        $this->name = $name;
        $this->arguments = $arguments;
    }
    
    public function calculate(Context $context) {
        return new Value($context->getFunctionManager()->executeFunction(
            $this->name, 
            array_map(function($arg) use ($context) { 
                return $arg->calculate($context)->getRawValue();
            }, $this->arguments)));
    }
    
}
