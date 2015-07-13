<?php

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;
use DVL\Struct\Value;

abstract class BaseBooleanExpression extends BaseExpression {
    
    public function calculate(Context $context) {
        return new Value($context, $this->calculateRaw($context));
    }
    
    public abstract function calculateRaw(Context $context);
    
}
