<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of TernaryValidation
 *
 * @author User
 */
class TernaryValidation extends BaseValidation {
    
    public $condition;
    public $positiveNestedValidation;
    public $negativeNestedValidation;
    
    function __construct(Expression $condition, BaseValidation $positive, BaseValidation $negative = null) {
        $this->condition = $condition;
        $this->positiveNestedValidation = $positive;
        $this->negativeNestedValidation = $negative;
    }
    
    public function execute(Context $context) {
        //TODO: what should happen when condition calculation fails?
        if ($this->condition->calculate()->isTrue()) {
            $this->positiveNestedValidation->execute($context);
        } else {
            if ($this->negativeNestedValidation !== null) {
                $this->negativeNestedValidation->execute($context);
            }
        }
    }
    
}
