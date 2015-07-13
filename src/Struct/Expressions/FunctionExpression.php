<?php

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;
use DVL\Struct\Value;

class FunctionExpression extends BaseExpression {
    
    private $name;
    private $arguments;
    
    function __construct($name, array $arguments = []) {
        $this->name = $name;
        $this->arguments = $arguments;
    }
    
    public function addArgument($arg) {
        $this->arguments[] = $arg;
    }
    
    public function calculate(Context $context) {
        return new Value(
            $context, 
            $context->getFunctionManager()->executeFunction( 
                $this->name, 
                array_map(function($arg) use ($context) { 
                    return $arg->calculate($context)->getRawValue();
                }, $this->arguments)
            )
        );
    }
    
}
