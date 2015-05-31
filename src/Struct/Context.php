<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL\Struct;

use DVL\DVLValidator;

/**
 * Description of Context
 *
 * @author User
 */
class Context {
    
    private $self;
    private $variables;
    private $validator;
    
    function __construct(DVLValidator $validator, $self, array $variables = []) {
        $this->validator = $validator;
        $this->variables = $variables;
        if ($self instanceof Value) {
            $this->self = $self;
        } else {
            $this->self = new Value($this, $self);
        }
    }
    
    public static function createFromContextWithI(Context $context, Value $self) {
        return new Context(
            $context->validator,
            $context->self, 
            [ 'i' => $self ] + $context->variables
        );
    }
    
    public static function createFromContextWithThis(Context $context, Value $self) {
        return new Context($context->validator, $self, $context->variables);
    }
    
    public static function createFromContext(Context $context) {
        return new Context($context->validator, $context->self, $context->variables);
    }
    
    public function setThis($self) {
        $this->self = $self;
    }
        
    public function getThis() {
        return $this->self;
    }
    
    public function setVariable($name, Value $value) {
        $this->variables[$name] = $value;
    }
    
    public function getVariable($name) {
        return $this->variables[$name];
    }
    
    public function getAllVariables() { 
        return $this->variables;
    }
    
    public function getAdapterManager() {
        return $this->validator->getAdapterManager();
    }
    
    public function getFunctionManager() {
        return $this->validator->getFunctionManager();
    }
    
}
