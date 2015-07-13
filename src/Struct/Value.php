<?php

namespace DVL\Struct;

use DVL\Struct\Exceptions\TypeException;
use DVL\Struct\Exceptions\KeyNotFoundValidationException;

class Value {
    
    const STRING_INVERSE_EXCEPTION_MESSAGE = "String inverse exception";
    
    const ARRAY_AS_STRING_REPRESENTATION = "array";
    
    const TYPE_BOOLEAN = 0;
    const TYPE_STRING = 1;
    const TYPE_NUMERIC = 2;
    const TYPE_ARRAY = 3;
    const TYPE_NULL = 4;
    
    public $type;
    public $value;
    private $context;
    
    function __construct(Context $context, $value) {
        $this->context = $context;
        $this->value = $context->getAdapterManager()->convertVariableToNative($this->context, $value);
        if (is_numeric($value)) {
            $this->type = static::TYPE_NUMERIC;
        } else if (is_bool($value)) {
            $this->type = static::TYPE_BOOLEAN;
        } else if (is_string($value)) {
            $this->type = static::TYPE_STRING;
        } else if (is_array($value)) {
            $this->value = $this->wrapArrayItems($this->value);
            $this->type = static::TYPE_ARRAY;
        } else if (is_null($value)) {
            $this->type = static::TYPE_NULL;
        }
    }
    
    private static $TYPE_NAMES = [
        Value::TYPE_BOOLEAN => 'boolean',
        Value::TYPE_ARRAY => 'array',
        Value::TYPE_NUMERIC => 'numeric',
        Value::TYPE_STRING => 'string',
        Value::TYPE_NULL => 'null',
    ];
    
    private static function typeToString($type) {
        return static::$TYPE_NAMES[$type];
    }

    private function wrapArrayItems(array $variable) {
        $wrappedValues = array();
        foreach ($variable as $key => $value) {
            if ($value instanceof Value) {
                $wrappedValues[$key] = $value;
            } else {
                $wrappedValues[$key] = $this->wrapVariable($value);
            }
        }
        return $wrappedValues;
    }
    
    private function wrapVariable($variable) {
        $variable = $this
                ->context
                ->getAdapterManager()
                ->convertVariableToNative($this->context, $variable);
        $val = null;
        if (is_array($variable)) {
            $val = new Value($this->context, $this->wrapArrayItems($variable));
        } else {
            $val = new Value($this->context, $variable);
        }
        return $val;
    }
    
    public function isTrue() {
        return $this->type == static::TYPE_BOOLEAN && $this->value == true;
    }
    
    public function isFalse() {
        return $this->type == static::TYPE_BOOLEAN && $this->value == false;
    }
    
    public function isString() {
        return $this->type == static::TYPE_STRING;
    }
    
    public function isNumeric() {
        return $this->type == static::TYPE_NUMERIC;
    }
    
    public function isArray() {
        return $this->type == static::TYPE_ARRAY;
    }
    
    public function asArray() {
        return $this->type == static::TYPE_ARRAY ? $this->value : [ $this->value ];
    }
    
    public function getArrayWithTypeException() {
        if ($this->type != static::TYPE_ARRAY) {
            throw new TypeException(
                    static::typeToString($this->type), 
                    static::typeToString(static::TYPE_ARRAY), 
                    $this->value);
        }
        
        return $this->value;
    }
    
    public function getRawArrayWithTypeException() {
        if ($this->type != static::TYPE_ARRAY) {
            throw new TypeException(
                    static::typeToString($this->type), 
                    static::typeToString(static::TYPE_ARRAY), 
                    $this->value);
        }
        
        $result = [];
        foreach ($this->value as $key => $value) {
            $result[$key] = $value->isArray() ? 
                $value->getRawArrayWithTypeException() : 
                $value->getRawValue();
        }
        
        return $result;
    }
    
    public function getRawValue() {
        return $this->value;
    }
    
    public function addElement($element, $key = null) {
        if ($this->type != static::TYPE_ARRAY) {
            throw new TypeException(
                    static::typeToString($this->type), 
                    static::typeToString(static::TYPE_ARRAY));
        }
        
        if ($key !== null) {
            $this->keys[] = $key;
        }
        
        $this->value[] = $element;
    }
    
    public function getBooleanWithTypeException() {
        if ($this->type != static::TYPE_BOOLEAN) {
            throw new TypeException(
                    static::typeToString($this->type), 
                    static::typeToString(Value::TYPE_BOOLEAN), 
                    $this->value);
        }
        
        return $this->value;
    }
    
    public function getNumericWithTypeException() {
        if ($this->type != static::TYPE_NUMERIC) {
            throw new TypeException(
                    static::typeToString($this->type), 
                    static::typeToString(Value::TYPE_NUMERIC), 
                    $this->value);
        }
        
        return $this->value;
    }
    
    public function getStringWithTypeException() {
        if ($this->type != static::TYPE_STRING) {
            throw new TypeException(
                    static::typeToString($this->type), 
                    static::typeToString(Value::TYPE_STRING), 
                    $this->value);
        }
        
        return $this->value;
    }
    
    public function asString() {
        if ($this->type == static::TYPE_ARRAY) {
            //TODO: maybe we need some other array representation
            return static::ARRAY_AS_STRING_REPRESENTATION;
        } else if ($this->type == static::TYPE_BOOLEAN) {
            return $this->value ? 'true' : 'false';
        } else {
            return (string)$this->value;
        }
    }
    
    public static function isEqualWithTypeException(Value $val1, Value $val2) {
        if ($val1->type !== $val2->type) {
            throw new TypeException(
                    static::typeToString($val1->type), 
                    static::typeToString($val2->type));
        }
        
        if ($val1->type == static::TYPE_ARRAY) {
            foreach ($val1->value as $key => $val) {
                if (isset($val2->value[$key])) {
                    return static::isEqualWithTypeException($val, $val2->value[$key]);
                } else {
                    throw new KeyNotFoundValidationException();
                }
            }
        } else {
            return $val1->value == $val2->value;
        }
    }
    
}
