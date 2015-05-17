<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of WrappersFactory
 *
 * @author User
 */
class AdapterManager {
    
    private $classArrayAdapters = [];
    private $arrayNativeArrayAdapter;
    private $objectNativeArrayAdapter;
    
    public function registerArrayNativeArrayAdapter( $adapterName ) {
        $this->arrayNativeArrayAdapter = $adapterName;
    }
    
    public function registerObjectNativeArrayAdapter( $adapterName ) {
        $this->objectNativeArrayAdapter = $adapterName;
    }
    
    public function registerClassArrayAdapter($className, $adapterName) {
        $this->classArrayAdapters[$className] = $adapterName;
    }
    
    public function instantiateAdapterForVariable($variable) {
        $adapter = null;
        if (is_array($variable)) {
            $adapter = new $this->arrayNativeArrayAdapter($variable);
        } else if (is_object($variable)) {
            $className = get_class($variable);
            if (isset($this->classArrayAdapters[$className])) {
                $adapter = new $this->classArrayAdapters[$className]($variable);
            } else {
                $adapter = new $this->objectNativeArrayAdapter($variable);
            }
        }
        
        if ($adapter === null) {
            throw new AdapterNotFoundException();
        }
        
        if (!($adapter instanceof IArrayAdapter)) {
            throw new AdapterInterfaceLostException();
        }
        
        return $adapter;
    }
    
}
