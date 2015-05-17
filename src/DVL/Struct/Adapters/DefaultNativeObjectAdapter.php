<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

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
    
    public function getAllKeys() {
        return array_keys(get_object_vars($this->variable));
    }
    
    public function hasKey($key) {
        return isset($this->variable->$key);
    }

    public function getByKey($key) {
        return $this->variable->$key;
    }

}
