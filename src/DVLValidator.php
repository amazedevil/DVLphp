<?php

namespace DVL;

use DVL\Struct\Context;
use DVL\Struct\AdapterManager;
use DVL\Struct\FunctionManager;
use DVL\Struct\Exceptions\ValidationException;
use DVL\Struct\Exceptions\ArrayItemValidationException;

class DVLValidator {
    
    private $adapterManager;
    private $functionManager;
    private $validation;
    
    private $lastException;
    
    function __construct($source, $options = null) {
        $this->lastException = null;
        $this->adapterManager = new AdapterManager();
        $this->functionManager = new FunctionManager();
        if (is_string($source)) {
            $this->compile($source);
        } else if ($source instanceof BaseValidation) {
            $this->validation = $source;
        }
        if (isset($options['functions'])) {
            foreach ($options['functions'] as $name => $function) {
                $this->functionManager->addFunction($name, $function);
            }
        }
    }
    
    public function compile($source) {
        $parser = new DVLParser($source);
        $res = $parser->match_ValidationControl();
        if ( $res === false ) {
            return false;
        } else {
            $this->validation = $res['validation'];
            return true;
        }
    }
    
    public function getAdapterManager() {
        return $this->adapterManager;
    }
    
    public function getFunctionManager() {
        return $this->functionManager;
    }
    
    public function addFunction($name, $function) {
        $this->functionManager->addFunction($name, $function);
    }
    
    public function getLastException() {
        return $this->lastException;
    }
    
    public function getLastErrorMessage() {
        return $this->lastException != null ? $this->lastException->getMessage() : null;
    }
    
    public function getLastErrorTag() {
        return $this->lastException != null ? $this->lastException->getTag() : null;
    }
    
    public function validate($data) {
        $this->lastException = null;
        try {
            $this->validation->execute(new Context($this, $data));
        } catch (ValidationException $e) {
            $this->lastException = $e;
        } catch (ArrayItemValidationException $e) {
            $this->lastException = $e;
        }
        return $this->lastException === null;
    }
    
    public static function sValidate($data, $source, $options = null, &$exception = null) {
        $validator = null;
        if (is_string($source)) {
            $validator = new DVLValidator($source, $options);
        } else if ($source instanceof DVLValidator) {
            $validator = $source;
        }
        
        if ($validator->validate($data)) {
            return true;
        } else {
            if ($exception !== null) {
                $exception = $validator->lastException;
            }
            return false;
        }
    }
    
}
