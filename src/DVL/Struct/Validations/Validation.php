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
    
    private function processMessage(Context $context, BaseValidationException $e, $message) {
        $replacements = [];
        foreach ($context->getAllVariables() as $key => $value) {
            $replacements["\{$key\}"] = $value;
        }
        if ($e->getInvalidValue() !== null) {
            $replacements['{invalid}'] = $e->getInvalidValue();
        }
        return strtr($message, $replacements);
    }
    
    public function execute(Context $context) {
        try {
            if ($this->expression->calculate($context)->isFalse()) {
                throw new FalseResultValidationException();
            }
        } catch (BaseValidationException $e) {
            throw new ValidationException($e, $this->processMessage($e));
        }
    }
    
}