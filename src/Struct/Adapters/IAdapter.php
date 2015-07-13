<?php

namespace DVL\Struct\Adapters;

interface IAdapter {
    
    public function isConvertableVariable($variable);
    
    public function convertToNativeVariable($variable);
    
}
