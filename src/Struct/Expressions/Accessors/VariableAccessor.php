<?php

namespace DVL\Struct\Expressions\Accessors;

use DVL\Struct\Context;
use DVL\Struct\Value;

class VariableAccessor extends BaseAccessor {
    
    private $name;
    
    function __construct($name) {
        $this->name = $name;
    }
    
    public function getValue(Context $context, Value $variable = null) {
        return $context->getVariable($this->name);
    }
    
}
