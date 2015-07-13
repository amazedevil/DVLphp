<?php

namespace DVL\Struct\Validations;

use DVL\Struct\Expressions\BaseExpression;
use DVL\Struct\Context;
use DVL\Struct\Exceptions\FalseResultValidationException;
use DVL\Struct\Exceptions\BaseValidationException;
use DVL\Struct\Exceptions\ValidationException;

class Validation extends BaseValidation {
    
    private $messageExpression;
    private $tagExpression;
    private $expression;
    
    function __construct(BaseExpression $expression, $message = null, $tag = null) {
        $this->expression = $expression;
        $this->setMessageExpression($message);
        $this->setTagExpression($tag);
    }
    
    public function setMessageExpression($expression) {
        $this->messageExpression = $expression;
    }
    
    public function setTagExpression($expression) {
        $this->tagExpression = $expression;
    }
    
    private function processMessage(Context $context, BaseValidationException $e, $message) {
        $replacements = [];
        foreach ($context->getAllVariables() as $key => $value) {
            $replacements["{{$key}}"] = $value->asString();
        }
        if ($e->getInvalidValue() !== null) {
            $replacements['{invalid}'] = $e->getInvalidValue();
        }
        $replacements['{this}'] = $context->getThis()->asString();
        return strtr($message, $replacements);
    }
    
    public function execute(Context $context) {
        try {
            if ($this->expression->calculate($context)->isFalse()) {
                throw new FalseResultValidationException();
            }
        } catch (BaseValidationException $e) {
            $message = $this->messageExpression != null ?
                $this->messageExpression->calculate($context)->getStringWithTypeException() :
                null;
            $tag = $this->tagExpression != null ?
                $this->tagExpression->calculate($context)->getStringWithTypeException() :
                null;
            throw new ValidationException(
                $e, 
                $this->processMessage( $context, $e, $message ),
                $tag
            );
        }
    }
    
}