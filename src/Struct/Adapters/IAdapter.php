<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Adapters;

/**
 * Description of ArrayAdapter
 *
 * @author User
 */
interface IAdapter {
    
    public function isConvertableVariable($variable);
    
    public function convertToNativeVariable($variable);
    
}