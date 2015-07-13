<?php

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;

class BooleanConstExpression extends BaseBooleanExpression {
    
    private $value;
    
    function __construct($value) {
        if (is_string($value)) {
            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
        $this->value = $value;
    }
    
    public function calculateRaw(Context $context) {
        return $this->value;
    }
    
}
