<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;

/**
 * Description of ArithmeticConstExpression
 *
 * @author User
 */
class ArithmeticConstExpression extends BaseArithmeticExpression {
    
    private $value;
    
    function __construct($value) {
        $this->value = floatval($value);
    }
    
    public function calculateRaw(Context $context) {
        return $this->value;
    }
    
}
