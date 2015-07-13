<?php

namespace DVL\Struct\Expressions\Accessors;

use DVL\Struct\Context;
use DVL\Struct\Value;

abstract class BaseAccessor {
    
    public abstract function getValue(Context $context, Value $variable);
    
}
