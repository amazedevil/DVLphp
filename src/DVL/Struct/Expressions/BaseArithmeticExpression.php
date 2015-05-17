<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of ArithmeticExpression
 *
 * @author User
 */
abstract class BaseArithmeticExpression extends BaseExpression {
    
    public function calculate(Context $context) {
        return new Value($context, $this->calculateRaw($context));
    }
    
    public function calculateRaw(Context $context);
    
}
