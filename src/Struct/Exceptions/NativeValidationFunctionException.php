<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Exceptions;

/**
 * Description of NativeFunctionException
 *
 * @author User
 */
class NativeValidationFunctionException extends BaseValidationException {
    
    const FUNC_INT_TYPE_FAILED = 1;
    const FUNC_STRING_TYPE_FAILED = 2;
    const FUNC_ARRAY_TYPE_FAILED = 3;
    const FUNC_BOOL_TYPE_FAILED = 3;
    
}
