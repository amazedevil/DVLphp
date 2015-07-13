<?php

namespace DVL\Struct\Validations;

use DVL\Struct\Context;

class GroupValidation extends BaseValidation {
    
    private $validations;
    
    function __construct($validations = []) {
        $this->validations = $validations;
    }
    
    function addValidation(BaseValidation $validation) {
        $this->validations[] = $validation;
    }
    
    public function execute(Context $context) {
        foreach ($this->validations as $validation) {
            $validation->execute($context);
        }
    }
}
