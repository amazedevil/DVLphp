<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;

/**
 * Description of ArithmeticBinaryExpression
 *
 * @author User
 */
class ArithmeticBinaryExpression extends BaseArithmeticExpression {
    
    const WRONG_TYPE_EXCEPTION_MESSAGE = "Unknown binary arithmetic expression type %d";
    
    const TYPE_MUL = 1;
    const TYPE_DIV = 2;
    const TYPE_MOD = 3;
    const TYPE_PLUS = 4;
    const TYPE_MINUS = 5;
    
    private $type;
    private $operand1;
    private $operand2;
    
    function __construct($type, 
            BaseExpression $operand1, 
            BaseExpression $operand2) {
        $this->type = $type;
        $this->operand1 = $operand1;
        $this->operand2 = $operand2;
    }
    
    private function calcValue1(Context $context) {
        return $this->operand1->calculate($context);
    }
    
    private function calcValue2(Context $context) {
        return $this->operand2->calculate($context);
    }
    
    public function calculateRaw(Context $context) {
        switch ($this->type) {
            case static::TYPE_MUL:
                return $this->calcValue1($context)->getNumericWithTypeException() * 
                    $this->calcValue2($context)->getNumericWithTypeException();
            case static::TYPE_DIV:
                return $this->calcValue1($context)->getNumericWithTypeException() / 
                    $this->calcValue2($context)->getNumericWithTypeException();
            case static::TYPE_MOD:
                return $this->calcValue1($context)->getNumericWithTypeException() % 
                    $this->calcValue2($context)->getNumericWithTypeException();
            case static::TYPE_PLUS:
                return $this->calcValue1($context)->getNumericWithTypeException() +
                    $this->calcValue2($context)->getNumericWithTypeException();
            case static::TYPE_MINUS:
                return $this->calcValue1($context)->getNumericWithTypeException() -
                    $this->calcValue2($context)->getNumericWithTypeException();
            default:
                throw new ValidatorBinaryStructureException(
                        sprintf(static::WRONG_TYPE_EXCEPTION_MESSAGE, $this->type)
                    );
        }
    }
    
}
