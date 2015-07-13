<?php

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;

class ArithmeticConstExpression extends BaseArithmeticExpression {
    
    private $value;
    
    function __construct($value) {
        $this->value = floatval($value);
    }
    
    public function calculateRaw(Context $context) {
        return $this->value;
    }
    
}
