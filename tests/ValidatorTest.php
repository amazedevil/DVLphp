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
    
    public function testValidatorArithmetics() {
        
        $passingTests = array(
            '1 < 2' => null,
        );
        
        foreach ($passingTests as $expression => $data) {
            $validator = new DVLValidator( $expression );
            $this->assertTrue($validator->validate($data));
        }
        
    }
    
}
