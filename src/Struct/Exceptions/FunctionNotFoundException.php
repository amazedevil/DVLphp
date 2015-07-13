<?php

namespace DVL\Struct\Exceptions;

use Exception;

class FunctionNotFoundException extends Exception {
    
    private $functionName;
    
    function __construct($functionName) {
        $this->functionName = $functionName;
    }
    
}
