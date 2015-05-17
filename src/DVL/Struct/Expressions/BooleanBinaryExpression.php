<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of BooleanAndExpression
 *
 * @author User
 */
class BooleanBinaryExpression extends BaseBooleanExpression {
    
    const WRONG_TYPE_EXCEPTION_MESSAGE = "Unknown binary expression type %d";
    
    const TYPE_AND = 1;
    const TYPE_OR = 2;
    const TYPE_GREATER = 3;
    const TYPE_GREATER_OR_EQUAL = 4;
    const TYPE_LESS = 5;
    const TYPE_LESS_OR_EQUAL = 6;
    const TYPE_EQUAL = 7;
    const TYPE_NOT_EQUAL = 8;
    
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
            case BooleanBinaryExpression::TYPE_AND:
                return $this->calcValue1($context)->isTrue() && 
                    $this->calcValue2($context)->isTrue();
            case BooleanBinaryExpression::TYPE_OR:
                try {
                    return $this->calcValue1($context)->isTrue();
                } catch (BaseValidationException $e) {
                    return $this->calcValue2($context)->isTrue();
                }
            case BooleanBinaryExpression::TYPE_GREATER:
                return Value::isGreaterThroughException(
                        $this->calcValue1($context), 
                        $this->calcValue2($context));
            case BooleanBinaryExpression::TYPE_GREATER_OR_EQUAL:
                return Value::isGreaterOrEqualThroughException(
                        $this->calcValue1($context), 
                        $this->calcValue2($context));
            case BooleanBinaryExpression::TYPE_LESS:
                return Value::isLessThroughException(
                        $this->calcValue1($context), 
                        $this->calcValue2($context));
            case BooleanBinaryExpression::TYPE_LESS_OR_EQUAL:
                return Value::isLessOrEqualThroughException(
                        $this->calcValue1($context),
                        $this->calcValue2($context));
            case BooleanBinaryExpression::TYPE_EQUAL:
                return Value::isEqualThroughException(
                        $this->calcValue1($context), 
                        $this->calcValue2($context));
            case BooleanBinaryExpression::TYPE_NOT_EQUAL:
                return Value::isNotEqualThroughException(
                        $this->calcValue1($context), 
                        $this->calcValue2($context));
            default:
                throw new ValidatorStructureException(
                        sprintf(BooleanBinaryExpression::WRONG_TYPE_EXCEPTION_MESSAGE),
                        $this->type);
        }
    }
    
}
