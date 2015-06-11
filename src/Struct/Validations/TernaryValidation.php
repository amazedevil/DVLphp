<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Validations;

use DVL\Struct\Expressions\BaseExpression;
use DVL\Struct\Context;
use DVL\Struct\Validations\BaseValidation;
use DVL\Struct\Exceptions\BaseValidationException;

/**
 * Description of TernaryValidation
 *
 * @author User
 */
class TernaryValidation extends BaseValidation {
    
    private $condition;
    private $positiveNestedValidation;
    private $negativeNestedValidation;
    
    function __construct(BaseExpression $condition, BaseValidation $positive = null, BaseValidation $negative = null) {
        $this->condition = $condition;
        $this->positiveNestedValidation = $positive;
        $this->negativeNestedValidation = $negative;
    }
    
    public function setPositive(BaseValidation $positive) {
        $this->positiveNestedValidation = $positive;
    }
    
    public function setNegative(BaseValidation $negative) {
        $this->negativeNestedValidation = $negative;
    }
    
    public function hasPositive() {
        return $this->positiveNestedValidation !== null;
    }
    
    public function execute(Context $context) {
        $result = true;
        try {
            $result = $this->condition->calculate($context)->isTrue();
        } catch (BaseValidationException $e) {
            $result = false;
        }
        if ($result) {
            $this->positiveNestedValidation->execute($context);
        } else {
            if ($this->negativeNestedValidation !== null) {
                $this->negativeNestedValidation->execute($context);
            }
        }
    }
    
}
