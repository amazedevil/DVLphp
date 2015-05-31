<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Expressions\Accessors;

use DVL\Struct\Context;
use DVL\Struct\Value;

/**
 * Description of BaseAccessor
 *
 * @author User
 */
abstract class BaseAccessor {
    
    public abstract function getValue(Context $context, Value $variable);
    
}
