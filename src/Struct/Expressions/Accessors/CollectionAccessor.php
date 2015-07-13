<?php

namespace DVL\Struct\Expressions\Accessors;

use DVL\Struct\Expressions\BaseExpression;
use DVL\Struct\Context;
use DVL\Struct\Value;
use DVL\Struct\Exceptions\VariableNameNotFoundException;
use DVL\Struct\Exceptions\KeyNotFoundValidationException;

class CollectionAccessor extends BaseAccessor {
    
    const KEY_VARIABLE_NAME = 'i';
    
    private $selector;
    
    function __construct(BaseExpression $selector) {
        $this->selector = $selector;
    }
    
    private function isMatchesSelector(Context $context, $key) {
        $selectorResult = true;
        if ($this->selector !== null) {
            try {
                $selectorResult = $this->selector->calculate(
                        Context::createFromContextWithI(
                                $context, 
                                new Value($context, $key)));
                $selectorResult = $selectorResult->isTrue();
            } catch (BaseValidationException $e) {
                $selectorResult = false;
            }
        }
        return $selectorResult;
    }
    
    public function getValue(Context $context, Value $variable) {  
        $resultArray = [];
        $arrayVariable = $variable;
        
        //if it's string, it should be converted to array of chars
        if ($variable->isString()) {
            $arrayVariable = new Value($context, str_split($variable->value));
        }
        
        //trying to get selector value right now,
        //and if it's failing with no key var, business as usual
        if ($this->selector !== null) {
            try {
                $selectorValue = $this->selector->calculate($context);
                if ($selectorValue->isNumeric() || $selectorValue->isString()) {
                    if (isset($arrayVariable->value[$selectorValue->value])) {
                        return $arrayVariable->value[$selectorValue->value];
                    } else {
                        throw new KeyNotFoundValidationException();
                    }
                }
            } catch (VariableNameNotFoundException $ex) {
                if ($ex->getName() != CollectionAccessor::KEY_VARIABLE_NAME) {
                    throw $ex;
                }
            }
        }
        
        foreach ($arrayVariable->getArrayWithTypeException() as $key => $value) {
            if ($this->isMatchesSelector($context, $key)) {
                $resultArray[] = $value;
            }
        }
        
        return new Value($context, $resultArray);
    }

}
