<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Validations;

use DVL\Struct\Expressions\BaseExpression;
use DVL\Struct\Context;

/**
 * Description of UseValidation
 *
 * @author User
 */
class UseValidation extends BaseValidation {
    
    private $expression;
    private $nestedValidation;
    
    function __construct(BaseExpression $expression, BaseValidation $validation = null) {
        $this->expression = $expression;
        $this->nestedValidation = $validation;
    }
    
    public function setValidation(BaseValidation $validation) {
        $this->nestedValidation = $validation;
    }
    
    public function execute(Context $context) {        
        $this->nestedValidation->execute(
            Context::createFromContextWithThis($context, $this->expression->calculate($context))
        );
    }
    
}
