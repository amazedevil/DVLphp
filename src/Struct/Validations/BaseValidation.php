<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Validations;

use DVL\Struct\Context;

/**
 * Description of BaseValidation
 *
 * @author User
 */
abstract class BaseValidation {
    
    public abstract function execute(Context $context);
    
}
