<?php

namespace DVL\Struct\Exceptions;

class VariableNameNotFoundException extends BaseValidatorStructureException {
    
    private $name;
    
    function __construct($name) {
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }
    
}
