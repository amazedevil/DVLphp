<?php

namespace DVL\Struct\Adapters;

class DefaultNativeObjectAdapter implements IAdapter {
        
    public function convertToNativeVariable($variable) {
        return (array) $variable;
    }

    public function isConvertableVariable($variable) {
        return $variable instanceof stdClass;
    }

}
