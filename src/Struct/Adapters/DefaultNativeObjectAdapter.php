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
class DefaultNativeObjectAdapter implements IAdapter {
        
    public function convertToNativeVariable($variable) {
        return (array) $variable;
    }

    public function isConvertableVariable($variable) {
        return $variable instanceof stdClass;
    }

}
