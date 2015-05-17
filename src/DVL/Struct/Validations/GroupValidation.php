<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DVL;

/**
 * Description of GroupValidation
 *
 * @author User
 */
class GroupValidation extends BaseValidation {
    
    private $validations;
    
    function __construct($validations = []) {
        $this->validations = $validations;
    }
    
    function addValidator(BaseValidation $validation) {
        $this->validations[] = $validation;
    }
    
    public function execute(Context $context) {
        foreach ($this->validations as $validation) {
            $validation->execute($context);
        }
    }
}
