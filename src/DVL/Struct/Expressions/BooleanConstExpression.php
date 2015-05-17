<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of BooleanConstExpression
 *
 * @author User
 */
class BooleanConstExpression extends BaseBooleanExpression {
    
    private $value;
    
    function __construct($value) {
        $this->value = $value;
    }
    
    public function calculateRaw(Context $context) {
        return $this->value;
    }
    
}
