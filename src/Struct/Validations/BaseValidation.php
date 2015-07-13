<?php

namespace DVL\Struct\Validations;

use DVL\Struct\Context;

abstract class BaseValidation {
    
    public abstract function execute(Context $context);
    
}
