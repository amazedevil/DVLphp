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
 * Description of StringExpression
 *
 * @author User
 */
class StringConstExpression extends BaseExpression {
    
    private $value;
    
    function __construct($value) {
        $this->value = $value;
    }
    
    public function calculate(Context $context) {
        return new Value($this->value);
    }

}