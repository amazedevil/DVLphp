<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use DVL\DVLValidator;

/**
 * Description of ValidatorTests
 *
 * @author User
 */
class ValidatorTest extends PHPUnit_Framework_TestCase {
    
    private function processSuccessiveTestsArray($tests) { 
        foreach ($tests as $expression => $data) {
            $validator = new DVLValidator( $expression );
            $this->assertTrue(
                    $validator->validate($data), 
                    "Expression: \"$expression\"");
        }
    }
    
    private function processFailingTestsArray($tests) {
        foreach ($tests as $expression => $data) {
            $validator = new DVLValidator( $expression );
            $this->assertFalse(
                    $validator->validate($data), 
                    "Expression: \"$expression\"");
        }
    }
    
    public function testValidatorBooleanOperations() {
                
        $this->processSuccessiveTestsArray(array(
            '1 == 1' => null,
            '1 != 2' => null,
            '1 >= 1' => null,
            '2 >= 1' => null,
            '1 <= 1' => null,
            '1 <= 2' => null,
            '3 > 2' => null,
            '3 < 4' => null,
            '1 == 1 && 2 == 2' => null,
            '1 != 1 || 2 == 2' => null,
            '1 == 1 || 2 != 2' => null,
            '1 == 1 || 2 == 2' => null,
            'true' => null,
            '!false' => null,
        ));
        
        $this->processFailingTestsArray(array(
            '1 == 2' => null,
            '1 != 1' => null,
            '0 >= 1' => null,
            '1 <= 0' => null,
            '2 > 2' => null,
            '1 > 2' => null,
            '3 < 2' => null,
            '3 < 3' => null,
            '1 == 0 && 2 == 2' => null,
            '1 == 0 && 1 == 2' => null,
            '1 == 1 && 1 == 2' => null,
            '1 != 1 || 2 != 2' => null,
            'false' => null,
            '!true' => null,
        ));
        
    }
    
    public function testValidatorArithmeticOperations() {
        
        $this->processSuccessiveTestsArray(array(
            '1 == 1' => null,
            '1 + 1 == 2' => null,
            '2 * 2 == 4' => null,
            '2 + 2 * 2 == 6' => null,
            '2 % 2 == 0' => null,
            '4 % 3 == 1' => null,
            '-1 < 0' => null,
            '-1 + 1 == 0' => null,
        ));
        
        $this->processFailingTestsArray(array(
            '1 == 2' => null,
            '1 + 1 == 1' => null,
            '2 * 2 == 5' => null,
            '2 + 2 * 2 == 8' => null,
            '2 % 2 == 1' => null,
            '4 % 3 == 2' => null,
            '-1 > 0' => null,
            '-1 + 1 != 0' => null,
        ));
        
    }
    
    public function testValidatorVariableOperations() {
        
        $this->processSuccessiveTestsArray(array(
            'this == 1' => 1,
            'this + 1 == 2' => 1,
            'this * 2 == 4' => 2,
            '2 + this * 2 == 6' => 2,
            'this' => true,
            'this && true' => true,
            'this || false' => true,
            'false || this' => true,
        ));
        
        $this->processFailingTestsArray(array(
            'this != 1' => 1,
            '1 + this == 1' => 1,
            'this * 2 == 5' => 2,
            '2 + this * 2 != 6' => 2,
            'this' => false,
            'this && false' => true,
            'this || false' => false,
            'false || this' => false,
        ));
        
    }
    
    public function testValidatorVariableAccessors() {
        
        //TODO: make tests for native + custom functions
        
    }
    
    public function testValidatorFunctions() {
        
        //TODO: make tests for native + custom functions
        
    }
    
    public function testValidatorControlStructures() {
        
        $this->processSuccessiveTestsArray(array(
            '{ this }' => true,
            '{ true, 1 == 1, this == 1 }' => 1,
            '(this) this > 1' => 2,
            '(this + 1) this == 2' => 1,
            '$(this) value > 0' => [ 1, 2, 3 ],
            '$(this : v) v > 0' => [ 1, 2, 3 ],
            '$(this : k => v) k > v' => [ 2 => 1, 4 => 3, 8 => 5 ],
            '$(this : k => v) { k % 2 == 0, v % 2 == 1 }' => [ 2 => 1, 4 => 3, 8 => 5 ],
            '$(this : k1 => v1) $(v1 : k2 => v2) { k1 > v2, k2 < v2 }' => [ 3 => [ 1, 2 ], 5 => [ 3, 4 ], 7 => [ 5, 6 ] ],
            '(this) ? true' => true,
            '(this) ? false : true' => false,
            '(this) ? true : false' => true,
        ));
        
        $this->processFailingTestsArray(array(
            '{ this }' => false,
            '{ true, 1 != 1, this == 1 }' => 1,
            '{ false, 1 == 1, this == 1 }' => 1,
            '{ true, 1 == 1, this < 1 }' => 1,
            '{ false, 1 != 1, this > 1 }' => 1,
            '(this) this == 1' => 2,
            '(this - 1) this != 0' => 1,
            '$(this) value < 0' => [ 1, 2, 3 ],
            '$(this : v) v == 0' => [ 1, 2, 3 ],
            '$(this : k => v) k < v' => [ 2 => 1, 4 => 3, 8 => 5 ],
            '$(this : k => v) { k % 2 > 0, v % 2 < 1 }' => [ 2 => 1, 4 => 3, 8 => 5 ],
            '$(this : k1 => v1) $(v1 : k2 => v2) { k1 < v2, k2 > v2 }' => [ 3 => [ 1, 2 ], 5 => [ 3, 4 ], 7 => [ 5, 6 ] ],
            '(this) ? false' => true,
            '(this) ? true : false' => false,
            '(this) ? false : true' => true,
        ));
        
    }
    
}
