<?php

namespace DVL\Struct\Expressions\Accessors;

use DVL\Struct\Context;
use DVL\Struct\Value;
use DVL\Struct\Exceptions\KeyNotFoundValidationException;

class PropertyAccessor extends BaseAccessor {
    
    private $name;
    
    function __construct($name) {
        $this->name = $name;
    }
    
    public function getValue(Context $context, Value $variable) {
        $array = $variable->getArrayWithTypeException();
        if (isset($array[$this->name])) {
            return $array[$this->name];
        } else {
            throw new KeyNotFoundValidationException();
        }
    }

}
