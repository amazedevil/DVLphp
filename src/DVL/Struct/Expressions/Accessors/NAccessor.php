<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of NAccessor
 *
 * @author User
 */
class NAccessor extends BaseAccessor {
    
    public function getValue(Context $context, $variable) {
        return $context->getN();
    }
    
}
