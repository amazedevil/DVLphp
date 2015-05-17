<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of ForeachValidation
 *
 * @author User
 */
class ForeachValidation extends BaseValidation {
    
    private $expression;
    private $validation;
    private $keyName;
    private $valueName;
   
    function __construct(BaseExpression $expression, BaseValidation $validation, $keyName, $valueName) {
        $this->expression = $expression;
        $this->validation = $validation;
        $this->keyName = $keyName;
        $this->valueName = $valueName;
    }
    
    public function execute(Context $context) {
        foreach ($this
                ->expression
                ->calculate($context)
                ->getQueriedArrayWithTypeException() as $key => $value) {
            $itemContext = Context::createFromContext($context);
            $itemContext->setVariable($this->keyName, new Value($key));
            $itemContext->setVariable($this->valueName, new Value($value));
            try {
                $this->validation->execute($itemContext);
            } catch (BaseValidationException $e) {
                throw new ArrayItemValidationException($key, $e);
            }
        }
    }
    
}
