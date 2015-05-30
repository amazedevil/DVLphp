<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Validations;

use DVL\Struct\Expressions\BaseExpression;
use DVL\Struct\Context;
use DVL\Struct\Exceptions\FalseResultValidationException;

/**
 * Description of BaseValidation
 *
 * @author User
 */
class Validation extends BaseValidation {
    
    private $message;
    private $tag;
    private $expression;
    
    function __construct(BaseExpression $expression, $message = null, $tag = null) {
        $this->expression = $expression;
        $this->setMessage($message);
        $this->setTag($tag);
    }
    
    public function setMessage($message) {
        $this->message = $message;
    }
    
    public function setTag($tag) {
        $this->tag = $tag;
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
            throw new ValidationException($e, $this->processMessage($e), $this->tag);
        }
    }
    
}