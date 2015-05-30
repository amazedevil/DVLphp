<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;

/**
 * Description of VariableExpression
 *
 * @author User
 */
class VariableExpression extends BaseExpression {
    
    private $accessors;
    
    function __construct($accessors = []) {
        $this->accessors = $accessors;
    }
    
    public function addAccessor($accessor) {
        $accessors[] = $accessor;
    }
        
    public function calculate(Context $context) {
        $result = null;
        foreach ($this->accessors as $accessor) {
            $result = $accessor->getValue($context, $result);
        }
        return $result;
    }
    
}
