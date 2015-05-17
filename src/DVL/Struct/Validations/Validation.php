<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of BaseValidation
 *
 * @author User
 */
class Validation extends BaseValidation {
    
    private $message;
    private $expression;
    
    function __construct(BaseExpression $expression, $message = null) {
        $this->expression = $expression;
        $this->message = $message;
    }
    
    public function execute(Context $context) {
        if ($this->expression->calculate($context)->isFalse()) {
            //TODO: throw validation failed exception
        }
    }
    
}