<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of CollectionAccessor
 *
 * @author User
 */
class CollectionAccessor extends BaseAccessor {
    
    private $selector;
    
    function __construct(BaseExpression $selector) {
        $this->selector = $selector;
    }
    
    private function isMatchesSelector($key) {
        $selectorResult = true;
        if ($this->selector !== null) {
            try {
                $selectorResult = $this->selector->calculate(
                        Context::createFromContextWithN(
                                $context, 
                                new Value($context, $key)));
            } catch (BaseValidationException $e) {
                $selectorResult = false;
            }
        }
        return $selectorResult;
    }
    
    public function getValue(Context $context, $variable) {        
        $isQueried = $variable->isQueried();
        $resultArray = [];
        if ($isQueried) {
            foreach ($variable->getArrayWithTypeException() as $value) {
                foreach ($value->getArrayWithTypeException() as $key => $innerValue) {
                    if ($this->isMatchesSelector($key)) {
                        $resultArray[] = new Value(
                                $context, 
                                $innerValue->value, 
                                array_merge($innerValue->getKeys(), [ $key ]),
                                true);
                    }
                }
            }
        } else {
            foreach ($variable->getArrayWithTypeException() as $key => $value) {
                if ($this->isMatchesSelector($key)) {
                    $resultArray[] = new Value(
                            $context, 
                            $value->value, 
                            [ $key ],
                            true);
                }
            }
        }
        
        return new Value($context, $resultArray);
    }

}
