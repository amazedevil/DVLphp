<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct;

use DVL\Struct\Adapters\IAdapter;
use DVL\Struct\Exceptions\AdapterInterfaceLostException;
use DVL\Struct\Adapters\DefaultNativeObjectAdapter;

/**
 * Description of WrappersFactory
 *
 * @author User
 */
class AdapterManager {
    
    private $adapters = [];
    
    function __construct() {
        $this->registerAdapter(new DefaultNativeObjectAdapter());
    }
    
    public function registerAdapter( $adapter ) {
        array_unshift($this->adapters, $adapter);
    }
    
    private function isNativeVariable($variable) {
        return is_array($variable)
            || is_numeric($variable)
            || is_bool($variable)
            || is_string($variable);
    }
    
    public function convertVariableToNative(Context $context, $variable) {
                
        if (!$this->isNativeVariable($variable)) {
            foreach ($this->adapters as $adapter) {
                if ($adapter->isConvertableVariable($variable)) {
                    if (!($adapter instanceof IAdapter)) {
                        throw new AdapterInterfaceLostException();
                    }
                    return $adapter->convertToNativeVariable($variable);
                }
            }
        } else {
            return $variable;
        }
    }
    
}
