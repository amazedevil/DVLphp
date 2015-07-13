<?php

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;

abstract class BaseExpression {
    
    public abstract function calculate(Context $context);
    
}
