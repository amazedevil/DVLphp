<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct;

/**
 * Description of WrappersFactory
 *
 * @author User
 */
class AdapterManager {
    
    private $classArrayAdapters = [];
    private $objectNativeArrayAdapter;
    
    public function registerObjectNativeArrayAdapter( $adapterName ) {
        $this->objectNativeArrayAdapter = $adapterName;
    }
    
    public function registerClassArrayAdapter($className, $adapterName) {
        $this->classArrayAdapters[$className] = $adapterName;
    }
    
    public function convertVariableToNative(Context $context, $variable) {
        
        $value = null;
        $adapter = null;
        
        if (is_object($variable)) {
            $className = get_class($variable);
            if (isset($this->classArrayAdapters[$className])) {
                $adapter = new $this->classArrayAdapters[$className]($variable);
            } else {
                $adapter = new $this->objectNativeArrayAdapter($variable);
            }
        }
        
        if ($adapter !== null) {
            if (!($adapter instanceof IArrayAdapter)) {
                throw new AdapterInterfaceLostException();
            }
            $value = $adapter->getNativeArray();
        } else {
            $value = $variable;
        }
        
        return $value;
    }
    
}
