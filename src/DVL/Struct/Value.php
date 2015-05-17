<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of Value
 *
 * @author User
 */
class Value {
    
    const TYPE_BOOLEAN = 0;
    const TYPE_STRING = 1;
    const TYPE_NUMERIC = 2;
    const TYPE_ARRAY = 3;
    const TYPE_QUERIED_ARRAY = 4;
    
    public $type;
    public $value;
    private $context;
    
    function __construct(Context $context, $value, $isQueried = false) {
        $this->value = $value;
        $this->context = $context;
        if (is_numeric($value)) {
            $this->type = static::TYPE_NUMERIC;
        } else if (is_bool($value)) {
            $this->type = static::TYPE_BOOLEAN;
        } else if (is_string($value)) {
            $this->type = static::TYPE_STRING;
        } else if (is_array($value)) {
            $this->type = static::TYPE_ARRAY;
            $this->setQueried($isQueried);
        }
    }
    
    public function setQueried($isQueried = true) {
        if (is_array($this->value)) {
            $this->type = $isQueried ? static::TYPE_QUERIED_ARRAY : static::TYPE_ARRAY;
        }
    }
    
    public function isQueried() {
        return $this->type == static::TYPE_QUERIED_ARRAY;
    }
    
    public function isTrue() {
        return $this->type == Value::TYPE_BOOLEAN && $this->value == true;
    }
    
    public function isFalse() {
        return $this->type == Value::TYPE_BOOLEAN && $this->value == false;
    }
    
    public function asArray() {
        return in_array($this->type, [ static::TYPE_ARRAY, static::TYPE_QUERIED_ARRAY ]) ? 
                $this->value : 
                [ $this->value ];
    }
    
    public function getArrayWithTypeException() {
        if (!in_array($this->type, [ static::TYPE_ARRAY, static::TYPE_QUERIED_ARRAY ])) {
            throw new TypeException($this->type, static::TYPE_ARRAY);
        }
        
        return $this->value;
    }
    
    public function getQueriedArrayWithTypeException() {
        if ($this->type != static::TYPE_QUERIED_ARRAY) {
            throw new TypeException($this->type, static::TYPE_QUERIED_ARRAY);
        }
        
        return $this->value;
    }
    
    public function getRawValue() {
        return $this->value;
    }
    
    public function addElement($element, $key = null) {
        if ($this->type != Value::TYPE_ARRAY) {
            //TODO: throw some exception
        }
        
        if ($key !== null) {
            $this->keys[] = $key;
        }
        
        $this->value[] = $element;
    }
    
    public function getInverseValue() {
        switch ($this->type) {
            case static::TYPE_ARRAY: case TYPE_QUERIED_ARRAY:
                $values = [];
                foreach ($this->value as $value) {
                    $values[] = $value->getInverseValue();
                }
                return new Value( $this->context, $values, null, $this->isQueried() );
            case static::TYPE_BOOLEAN:
                return new Value( $this->context, !$this->value );
            case static::TYPE_NUMERIC:
                return new Value( $this->context, -$this->value );
            case static::TYPE_STRING:
                //TODO: throw type exception
                return null;
        }
    }
    
    public function getBooleanWithTypeException() {
        if ($this->type != Value::TYPE_BOOLEAN) {
            throw new TypeException($this->type, Value::TYPE_BOOLEAN);
        }
        
        return $this->value;
    }
    
    public function getNumericWithTypeException() {
        if ($this->type != Value::TYPE_NUMERIC) {
            throw new TypeException($this->type, Value::TYPE_NUMERIC);
        }
        
        return $this->value;
    }
    
    public static function isEqualWithTypeException(Value $val1, Value $val2) {
        if ($val1->type !== $val2->type) {
            throw new TypeException($val1->type, $val2->type);
        }
        
        //TODO: for arrays should be deep comparison
        return $val1->value == $val2->value;
    }
    
}
