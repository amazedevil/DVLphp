<?php

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;

class BooleanUnaryExpression extends BaseBooleanExpression {
    
    const WRONG_TYPE_EXCEPTION_MESSAGE = "Unknown unary boolean expression type %d";
    
    const TYPE_NOT = 1;
    
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
            case static::TYPE_NOT:
                return !$this->getValue($context)->getBooleanWithTypeException();
            default:
                throw new ValidatorBinaryStructureException(
                        sprintf(static::WRONG_TYPE_EXCEPTION_MESSAGE, $this->type)
                    );
        }
    }
    
}
