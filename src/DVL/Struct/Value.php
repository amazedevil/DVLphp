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
    private $keys;
    private $context;
    
    function __construct(Context $context, $value, $keys = null, $isQueried = false) {
        $this->value = $value;
        $this->keys = $keys;
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
    
    public function getKeys() {
        return $this->keys;
    }
    
    public function isTrue() {
        return $this->type == Value::TYPE_BOOLEAN && $this->value == true;
    }
    
    public function isFalse() {
        return $this->type == Value::TYPE_BOOLEAN && $this->value == false;
    }
    
    public function asArray() {
        return $this->type == Value::TYPE_ARRAY ? $this->value : [ $this->value ];
    }
    
    public function getArrayWithTypeException() {
        if ($this->type != Value::TYPE_ARRAY) {
            throw new TypeException($this->type, Value::TYPE_ARRAY);
        }
        
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
    
    private static function onEachItemOfTwoOperands( Value $operand1, Value $operand2, $callback ) {
        if ($operand1->type != Value::TYPE_QUERIED_ARRAY && $operand2->type != Value::TYPE_QUERIED_ARRAY) {
            $callback($operand1->value, $operand2->value);
        } else if ($operand1->type == Value::TYPE_QUERIED_ARRAY && $operand2->type != Value::TYPE_QUERIED_ARRAY) {
            foreach ($operand1->value as $item1) {
                try {
                    $callback($item1, $operand2->value);
                } catch (ValidationException $e) {
                    throw new ArrayItemValidationException([ $item1->keys ], $e);
                }
            }
        } else if ($operand1->type != Value::TYPE_QUERIED_ARRAY && $operand2->type == Value::TYPE_QUERIED_ARRAY) {
            foreach ($operand2->value as $item2) {
                try {
                    $callback($operand1->value, $item2);
                } catch (ValidationException $e) {
                    throw new ArrayItemValidationException([ $item2->keys ], $e);
                }
            }
        } else {
            foreach ($operand1->value as $item1) {
                foreach ($operand2->value as $item2) {
                    try {
                        $callback($item1, $item2);
                    } catch (ValidationException $e) {
                        throw new ArrayItemValidationException([ $item2->keys, $item1->keys ], $e);
                    }
                }
            }
        }
    }
    
    private function passCallback( array $operands, array $fixedValues, $callback )
    {
        $fixed = array_shift( $operands );
        $isFixedArray = $fixed->type == Value::TYPE_QUERIED_ARRAY;
        $values = $isFixedArray ? $fixed->value : [ $fixed ];
        
        $useCallback = count($operands) == 0;
        
        $results = [];
        
        foreach ($values as $item) {
            $currentFixedValues = $fixedValues;
            $currentFixedValues[] = $item;
            $keys = $isFixedArray ? [ $item->keys ] : false;
            if ($useCallback) {
                try {
                    $results[] = $callback($currentFixedValues);
                } catch (ValidationException $e) {
                    return new ArrayItemValidationException($keys ?: [], $e);
                }
            } else {
                $passResult = $this->passCallback($operands, $currentFixedValues, $callback);
                if ($passResult instanceof Exception) {
                    if ($keys !== false) {
                        $exception->addKeys($keys);
                    }
                    return $exception;
                } else {
                    $passResult = array_merge($results, $passResult);
                }
            }
        }
        
        return $results;
    }
    
    public static function onEachItem( array $operands, $callback )
    {
        $result = $this->passCallback($operands, [], $callback);
        if ($result instanceof Exception) {
            throw $result;
        }
        return count($result) > 1 ? $result : array_pop($result);
    }
    
    public static function isGreaterThroughException( Value $operand1, Value $operand2) {
        $this->onEachItemOfTwoOperands($operand1, $operand2, function($v1, $v2) {
            if (!($v1->getNumericWithTypeException() > $v2->getNumericWithTypeException())) {
                throw new FalseResultValidationException(FalseResultValidationException::OPERATION_GREATER);
            }
        });
        return true;
    }
    
    public static function isLessThroughException( Value $operand1, Value $operand2) {
        $this->onEachItemOfTwoOperands($operand1, $operand2, function($v1, $v2) {
            if (!($v1->getNumericWithTypeException() < $v2->getNumericWithTypeException())) {
                throw new FalseResultValidationException(FalseResultValidationException::OPERATION_LESS);
            }
        });
        return true;
    }
    
    public static function isGreaterOrEqualThroughException( Value $operand1, Value $operand2) {
        $this->onEachItemOfTwoOperands($operand1, $operand2, function($v1, $v2) {
            if (!($v1->getNumericWithTypeException() >= $v2->getNumericWithTypeException())) {
                throw new FalseResultValidationException(FalseResultValidationException::OPERATION_GREATER_OR_EQUAL);
            }
        });
        return true;
    }
    
    public static function isLessOrEqualThroughException( Value $operand1, Value $operand2) {
        $this->onEachItemOfTwoOperands($operand1, $operand2, function($v1, $v2) {
            if (!($v1->getNumericWithTypeException() <= $v2->getNumericWithTypeException())) {
                throw new FalseResultValidationException(FalseResultValidationException::OPERATION_LESS_OR_EQUAL);
            }
        });
        return true;
    }
    
    public static function isEqualThroughException( Value $operand1, Value $operand2) {
        if (!Value::isEqualWithTypeException($operand1, $operand2)) {
            throw new FalseResultValidationException(FalseResultValidationException::OPERATION_EQUAL);
        }
        return true;
    }
    
    public static function isNotEqualThroughException( Value $operand1, Value $operand2) {
        if (!Value::isEqualWithTypeException($operand1, $operand2)) {
            throw new FalseResultValidationException(FalseResultValidationException::OPERATION_EQUAL);
        }
        return true;
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
