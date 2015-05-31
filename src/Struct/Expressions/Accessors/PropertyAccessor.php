<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Expressions\Accessors;

use DVL\Struct\Context;
use DVL\Struct\Value;
use DVL\Struct\Exceptions\KeyNotFoundValidationException;

/**
 * Description of PropertyAccessor
 *
 * @author User
 */
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
