<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;

/**
 * Description of BaseExpression
 *
 * @author User
 */
abstract class BaseExpression {
    
    public abstract function calculate(Context $context);
    
}