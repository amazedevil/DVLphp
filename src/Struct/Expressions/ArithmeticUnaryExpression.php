<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;

/**
 * Description of ArithmeticUnaryExpression
 *
 * @author User
 */
class ArithmeticUnaryExpression extends BaseArithmeticExpression {
    
    const TYPE_MINUS = 1;
    
    private $type;
    private $operand;
    
    function __construct($type, BaseExpression $operand) {
        $this->type = $type;
        $this->operand = $operand;
    }
    
    private function getValue(Context $context) {
        return $this->operand->calculate($context);
    }
    
    public function calculateRaw(Context $context) {
        switch ($this->type) {
            case static::TYPE_MINUS:
                return $this->getValue($context)->getInverseValue();
            default:
                //TODO: throw error
        }
    }

}
