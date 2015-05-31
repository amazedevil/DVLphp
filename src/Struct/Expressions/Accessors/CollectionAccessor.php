<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Expressions\Accessors;

use DVL\Struct\Expressions\BaseExpression;
use DVL\Struct\Context;
use DVL\Struct\Value;

/**
 * Description of CollectionAccessor
 *
 * @author User
 */
class CollectionAccessor extends BaseAccessor {
    
    private $selector;
    
    function __construct(BaseExpression $selector = null) {
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
            } catch (BaseValidationException $e) {
                $selectorResult = false;
            }
        }
        return $selectorResult;
    }
    
    public function getValue(Context $context, Value $variable) {  
        $resultArray = [];
        $arrayVariable = $variable;
        if ($variable->isString()) {
            $arrayVariable = new Value($context, str_split($variable->value));
        }
        foreach ($arrayVariable->getArrayWithTypeException() as $key => $value) {
            if ($this->isMatchesSelector($context, $key)) {
                $resultArray[] = $value;
            }
        }
        
        return new Value($context, $resultArray, true);
    }

}
