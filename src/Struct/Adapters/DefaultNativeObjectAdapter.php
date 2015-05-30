<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Adapters;

/**
 * Description of DefaultNativeObjectAdapter
 *
 * @author User
 */
class DefaultNativeObjectAdapter implements IArrayAdapter {
    
    private $variable;
    
    function __construct($variable) {
        $this->variable = $variable;
    }
    
    public function getNativeArray() {
        return (array) $this->variable;
    }

}
