<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of Context
 *
 * @author User
 */
class Context {
    
    private $self;
    private $n;
    private $validator;
    
    function __construct(DVLValidator $validator, Value $self, Value $n = null) {
        $this->self = $self;
        $this->validator = $validator;
    }
    
    public static function createFromContextWithThis(Context $context, Value $self) {
        return new Context($context->validator, $self);
    }
    
    public static function createFromContextWithN(Context $context, Value $value) {
        return new Context($context->validator, $context->self, $value);
    }
    
    public function getThis() {
        return $this->self;
    }
    
    public function getN() {
        return $this->n;
    }
    
    public function getAdapterManager() {
        return $this->validator->getAdapterManager();
    }
    
    public function getFunctionManager() {
        return $this->validator->getFunctionManager();
    }
    
}
