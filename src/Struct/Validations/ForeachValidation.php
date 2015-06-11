<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Validations;

use DVL\Struct\Context;
use DVL\Struct\Expressions\BaseExpression;
use DVL\Struct\Value;
use DVL\Struct\Exceptions\BaseValidationException;
use DVL\Struct\Exceptions\ValidationException;
use DVL\Struct\Exceptions\ArrayItemValidationException;

/**
 * Description of ForeachValidation
 *
 * @author User
 */
class ForeachValidation extends BaseValidation {
    
    const DEFAULT_KEY_NAME = 'key';
    const DEFAULT_VALUE_NAME = 'value';
    
    private $expression;
    private $validation;
    public $keyName;
    public $valueName;
   
    function __construct(BaseExpression $expression, BaseValidation $validation = null, $keyName = null, $valueName = null) {
        $this->expression = $expression;
        $this->validation = $validation;
        $this->keyName = $keyName;
        $this->valueName = $valueName;
    }
    
    private function getKeyName() {
        if ($this->keyName === null) {
            return static::DEFAULT_KEY_NAME;
        } else {
            return $this->keyName;
        }
    }
    
    private function getValueName() {
        if ($this->valueName === null) {
            return static::DEFAULT_VALUE_NAME;
        } else {
            return $this->valueName;
        }
    }
    
    public function setValidation(BaseValidation $validation) {
        $this->validation = $validation;
    }
    
    public function execute(Context $context) {
        $arr = null;
        try {
            $arr = $this->expression->calculate($context)->getArrayWithTypeException();
        } catch (BaseValidationException $e) {
            throw new ValidationException( $e, null );
        }
        foreach ($arr as $key => $value) {
            $itemContext = Context::createFromContext($context);
            $itemContext->setVariable($this->getKeyName(), new Value($context, $key));
            $itemContext->setVariable($this->getValueName(), $value);
            try {
                $this->validation->execute($itemContext);
            } catch (BaseValidationException $e) {
                throw new ArrayItemValidationException($key, $e);
            }
        }
    }
    
}
