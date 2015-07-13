<?php

namespace DVL\Struct\Exceptions;

class NativeValidationFunctionException extends BaseValidationException {
    
    const FUNC_INT_TYPE_FAILED = 1;
    const FUNC_STRING_TYPE_FAILED = 2;
    const FUNC_ARRAY_TYPE_FAILED = 3;
    const FUNC_BOOL_TYPE_FAILED = 3;
    
}
