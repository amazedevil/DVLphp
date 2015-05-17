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
    
    function __construct($name, $arguments = []) {
        $this->name = $name;
        $this->arguments = $arguments;
    }
    
    public function calculate(Context $context) {
        return new Value(Value::onEachItem($this->arguments, function() use ($context) {
            $context->getFunctionManager()->executeFunction($this->name);
        }));
    }
    
}
