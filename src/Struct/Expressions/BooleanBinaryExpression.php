<?php

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;
use DVL\Struct\Value;
use DVL\Struct\Exceptions\BaseValidationException;

class BooleanBinaryExpression extends BaseBooleanExpression {
    
    const WRONG_TYPE_EXCEPTION_MESSAGE = "Unknown binary boolean expression type %d";
    
    const TYPE_AND = 1;
    const TYPE_OR = 2;
    const TYPE_GREATER = 3;
    const TYPE_GREATER_OR_EQUAL = 4;
    const TYPE_LESS = 5;
    const TYPE_LESS_OR_EQUAL = 6;
    const TYPE_EQUAL = 7;
    const TYPE_NOT_EQUAL = 8;
    const TYPE_IN = 9;
    
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
            case static::TYPE_AND:
                return $this->calcValue1($context)->isTrue() && 
                    $this->calcValue2($context)->isTrue();
            case static::TYPE_OR:
                try {
                    if ($this->calcValue1($context)->isTrue()) {
                        return true;
                    }
                } catch (BaseValidationException $e) {
                    //We are ignoring first expression fail purposely,
                    //that's why 'or' is not my favourite operation
                }
                return $this->calcValue2($context)->isTrue();
            case static::TYPE_GREATER:
                return $this->calcValue1($context)->getNumericWithTypeException() >
                    $this->calcValue2($context)->getNumericWithTypeException();
            case static::TYPE_GREATER_OR_EQUAL:
                return $this->calcValue1($context)->getNumericWithTypeException() >=
                    $this->calcValue2($context)->getNumericWithTypeException();
            case static::TYPE_LESS:
                return $this->calcValue1($context)->getNumericWithTypeException() <
                    $this->calcValue2($context)->getNumericWithTypeException();
            case static::TYPE_LESS_OR_EQUAL:
                return $this->calcValue1($context)->getNumericWithTypeException() <=
                    $this->calcValue2($context)->getNumericWithTypeException();
            case static::TYPE_EQUAL:
                return Value::isEqualWithTypeException(
                        $this->calcValue1($context),
                        $this->calcValue2($context));
            case static::TYPE_NOT_EQUAL:
                return !Value::isEqualWithTypeException(
                        $this->calcValue1($context),
                        $this->calcValue2($context));
            case static::TYPE_IN:
                return in_array(
                        $this->calcValue1($context)->getRawValue(), 
                        $this->calcValue2($context)->getRawArrayWithTypeException());
            default:
                throw new ValidatorBinaryStructureException(
                        sprintf(static::WRONG_TYPE_EXCEPTION_MESSAGE, $this->type)
                    );
        }
    }
    
}
