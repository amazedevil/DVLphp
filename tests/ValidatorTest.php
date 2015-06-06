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
    
    public function testValidatorBooleanOperations() {
        
        $passingTests = array(
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
        );
        
        foreach ($passingTests as $expression => $data) {
            $validator = new DVLValidator( $expression );
            $this->assertTrue(
                    $validator->validate($data), 
                    "Expression: \"$expression\"");
        }
        
        $failingTests = array(
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
        );
        
        foreach ($failingTests as $expression => $data) {
            $validator = new DVLValidator( $expression );
            $this->assertFalse(
                    $validator->validate($data), 
                    "Expression: \"$expression\"");
        }
        
    }
    
    public function testValidatorArithmeticOperations() {
        
        $passingTests = array(
            '1 == 1' => null,
            '1 + 1 == 2' => null,
            '2 * 2 == 4' => null,
            '2 + 2 * 2 == 6' => null,
        );
        
        foreach ($passingTests as $expression => $data) {
            $validator = new DVLValidator( $expression );
            $this->assertTrue(
                    $validator->validate($data), 
                    "Expression: \"$expression\"");
        }
        
        $failingTests = array(
            '1 == 2' => null,
            '1 + 1 == 1' => null,
            '2 * 2 == 5' => null,
            '2 + 2 * 2 == 8' => null,
        );
        
        foreach ($failingTests as $expression => $data) {
            $validator = new DVLValidator( $expression );
            $this->assertFalse(
                    $validator->validate($data), 
                    "Expression: \"$expression\"");
        }
        
    }
    
    public function testValidatorVariableOperations() {
        
        $passingTests = array(
            'this == 1' => 1,
            'this + 1 == 2' => 1,
            'this * 2 == 4' => 2,
            '2 + this * 2 == 6' => 2,
            'this' => true,
            'this && true' => true,
            'this || false' => true,
            'false || this' => true,
        );
        
        foreach ($passingTests as $expression => $data) {
            $validator = new DVLValidator( $expression );
            $this->assertTrue(
                    $validator->validate($data), 
                    "Expression: \"$expression\"");
        }
        
        $failingTests = array(
            'this != 1' => 1,
            '1 + this == 1' => 1,
            'this * 2 == 5' => 2,
            '2 + this * 2 != 6' => 2,
            'this' => false,
            'this && false' => true,
            'this || false' => false,
            'false || this' => false,
        );
        
        foreach ($failingTests as $expression => $data) {
            $validator = new DVLValidator( $expression );
            $this->assertFalse(
                    $validator->validate($data), 
                    "Expression: \"$expression\"");
        }
        
    }
    
    public function testValidatorFunctions() {
        
        //TODO: make tests for native + custom functions
        
    }
    
    public function testValidatorControlStructures() {
        
        //TODO: make tests for use, foreach, ternary, group
        
    }
    
}
