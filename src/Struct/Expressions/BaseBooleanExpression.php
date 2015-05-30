<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;
use DVL\Struct\Value;

/**
 * Description of BooleanExpression
 *
 * @author User
 */
abstract class BaseBooleanExpression extends BaseExpression {
    
    public function calculate(Context $context) {
        return new Value($context, $this->calculateRaw($context));
    }
    
    public abstract function calculateRaw(Context $context);
    
}
