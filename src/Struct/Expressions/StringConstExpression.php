<?php

namespace DVL\Struct\Expressions;

use DVL\Struct\Context;
use DVL\Struct\Value;
use DVL\Struct\Expressions\BaseExpression;

class StringConstExpression extends BaseExpression {
    
    private static $STRING_PROCESSING_REPLACEMENTS = array(
        '\"' => '"',
        "\\'" => "'",
        "\\\\" => "\\",
    );
    
    private $value;
    
    function __construct($value) {
        $this->value = $this->process($value);
    }
    
    public function calculate(Context $context) {
        return new Value($context, $this->value);
    }
    
    private function process($str) {
        return strtr($str, static::$STRING_PROCESSING_REPLACEMENTS);
    }

}
