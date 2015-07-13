<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;
use DVL\Struct\Value;

class ArrayConstExpression extends BaseExpression {
    
    private $expressions = [];
    
    function __construct($expressions) {
        $this->expressions = $expressions;
    }
    
    public function addExpression(BaseExpression $expression) {
        $this->expressions[] = $expression;
    }

    public function calculate(Context $context) {
        $calculated = [];
        foreach ($this->expressions as $expression) {
            $calculated[] = $expression->calculate($context);
        }
        return new Value($context, $calculated);
    }

}
