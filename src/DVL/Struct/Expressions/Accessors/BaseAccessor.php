<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of BaseAccessor
 *
 * @author User
 */
abstract class BaseAccessor {
    
    public abstract function getValue(Context $context, $variable);
    
}
