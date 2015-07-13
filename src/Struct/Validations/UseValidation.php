<?php

namespace DVL\Struct\Validations;

use DVL\Struct\Expressions\BaseExpression;
use DVL\Struct\Context;
use DVL\Struct\Exceptions\ValidationException;
use DVL\Struct\Exceptions\BaseValidationException;

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
        $self = null;
        try {
            $self = $this->expression->calculate($context);
        } catch (BaseValidationException $e) {
            throw new ValidationException( $e, null );
        }
        $this->nestedValidation->execute(
            Context::createFromContextWithThis($context, $self)
        );
    }
    
}
