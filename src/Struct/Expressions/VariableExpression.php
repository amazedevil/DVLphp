<?php

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;

class VariableExpression extends BaseExpression {
    
    private $accessors;
    
    function __construct($accessors = []) {
        $this->accessors = $accessors;
    }
    
    public function addAccessor($accessor) {
        $this->accessors[] = $accessor;
    }
        
    public function calculate(Context $context) {
        $result = null;
        foreach ($this->accessors as $accessor) {
            $result = $accessor->getValue($context, $result);
        }
        return $result;
    }
    
}
