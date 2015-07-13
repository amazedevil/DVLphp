<?php

namespace DVL\Struct\Expressions\Accessors;

use DVL\Struct\Context;
use DVL\Struct\Value;

class ThisAccessor extends BaseAccessor {
    
    public function getValue(Context $context, Value $variable = null) {
        return $context->getThis();
    }
    
}
