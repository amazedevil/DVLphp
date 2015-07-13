<?php

namespace DVL;

use DVL\Struct\Expressions\ArithmeticConstExpression;
use DVL\Struct\Expressions\ArithmeticBinaryExpression;
use DVL\Struct\Expressions\ArithmeticUnaryExpression;
use DVL\Struct\Validations\Validation;
use DVL\Struct\Validations\UseValidation;
use DVL\Struct\Validations\TernaryValidation;
use DVL\Struct\Validations\GroupValidation;
use DVL\Struct\Validations\ForeachValidation;
use DVL\Struct\Expressions\VariableExpression;
use DVL\Struct\Expressions\StringConstExpression;
use DVL\Struct\Expressions\FunctionExpression;
use DVL\Struct\Expressions\BooleanUnaryExpression;
use DVL\Struct\Expressions\BooleanConstExpression;
use DVL\Struct\Expressions\BooleanBinaryExpression;
use DVL\Struct\Expressions\ArrayConstExpression;
use DVL\Struct\Expressions\Accessors\PropertyAccessor;
use DVL\Struct\Expressions\Accessors\VariableAccessor;
use DVL\Struct\Expressions\Accessors\ThisAccessor;
use DVL\Struct\Expressions\Accessors\CollectionAccessor;

use hafriedlander\Peg\Parser;

class DVLParser extends Parser\Basic {

	function whitespace() {
		$matched = preg_match( '/[\s]+/', $this->string, $matches, PREG_OFFSET_CAPTURE, $this->pos ) ;
		if ( $matched && $matches[0][1] == $this->pos ) {
			$this->pos += strlen( $matches[0][0] );
			return ' ';
		}
		return FALSE ;
	}

/*!* DVLParser

Number: /[0-9]+(\.[0-9]+)* /
String: "\'" val:(/([^'\\]|\\'|\\\\)* /) "\'" | '"' val:(/([^"\\]|\\"|\\\\)* /) '"' 
Name: !Boolean !This /[a-zA-Z_]+([a-zA-Z0-9_]*)/
Boolean: 'true' | 'false'
This: 'this' !/[a-zA-Z0-9_]/
Array: '[' > initial:Expression > ( ',' > additional:Expression > )* ']'
    function initial( &$result, $sub ) {
		$result['expression'] = new ArrayConstExpression([ $sub['expression'] ]);
	}
    function additional( &$result, $sub ) {
        $result['expression']->addExpression( $sub['expression'] );
    }

PropertySelector: '.' Name >
	function Name( &$result, $sub ) {
		$result['accessor'] = new PropertyAccessor($sub['text']);
	}
ArrayElementSelector: '[' > selector:Expression > ']' >
Variable: ( Name | This ) (PropertySelector | ArrayElementSelector)* >
	function Name( &$result, $sub ) {
		$result['variable'] = new VariableExpression([ new VariableAccessor($sub['text']) ]);
	}
	function This( &$result, $sub ) {
		$result['variable'] = new VariableExpression([ new ThisAccessor() ]);
	}
	function PropertySelector( &$result, $sub ) {
		$result['variable']->addAccessor($sub['accessor']);
	}
	function ArrayElementSelector( &$result, $sub ) {
		$result['variable']->addAccessor(new CollectionAccessor(
			isset($sub['selector']['expression']) ? $sub['selector']['expression'] : null
		));
	}

Function: Name '(' > ( Expression )? > ( ',' > Expression > )* ')' >
	function Name( &$result, $sub ) {
		$result['function'] = new FunctionExpression( $sub['text'] );
	}
	function Expression( &$result, $sub ) {
		$result['function']->addArgument($sub['expression']);
	}

Use: '(' > Expression > ')' > ValidationControl >
	function Expression( &$result, $sub ) {
		$result['validation'] = new UseValidation( $sub['expression'] );
	}
	function ValidationControl( &$result, $sub ) {
		$result['validation']->setValidation( $sub['validation'] );
	}
Ternary: '(' > Expression > ')' > '?' > ValidationControl > ( ':' > ValidationControl > )?
	function Expression( &$result, $sub ) {
		$result['validation'] = new TernaryValidation( $sub['expression'] );
	}
	function ValidationControl( &$result, $sub ) {
		if (!$result['validation']->hasPositive()) {
			$result['validation']->setPositive( $sub['validation'] );
		} else {
			$result['validation']->setNegative( $sub['validation'] );
		}
	}
Foreach: '$(' > Expression > ( ':' > key_value:Name > ( '=>' > value:Name )? )? > ')' > ValidationControl >
	function Expression( &$result, $sub ) {
		$result['validation'] = new ForeachValidation( $sub['expression'] );
	}
	function key_value( &$result, $sub ) {
		$result['validation']->valueName = $sub['text'];
	}
	function value( &$result, $sub ) {
		$result['validation']->keyName = $result['validation']->valueName;
		$result['validation']->valueName = $sub['text'];
	}
	function ValidationControl( &$result, $sub ) {
		$result['validation']->setValidation( $sub['validation'] );
	}
Group: '{' > ValidationControl > ( ',' > ValidationControl > )* '}' >
	function ValidationControl( &$result, $sub ) {
		if (!isset($result['validation'])) {
			$result['validation'] = new GroupValidation([ $sub['validation'] ]);
		} else {
			$result['validation']->addValidation( $sub['validation'] );
		}
	}
ValidationControl: Group | Foreach | Ternary | Use | Validation
	function *( &$result, $sub ) {
		$result['validation'] = $sub['validation'];
	}
Validation: Expression ( > '@' > message:String > ( '%' > tag:String )? )? >
	function Expression( &$result, $sub ) {
		$result['validation'] = new Validation( $sub['expression'] );
	}
	function message( &$result, $sub ) {
		$result['validation']->setMessageExpression(new StringConstExpression($sub['val']['text']));
	}
	function tag( &$result, $sub ) {
		$result['validation']->setTagExpression(new StringConstExpression($sub['val']['text']));
	}
    
Value: '!' Value > | String > | Boolean > | Number > | Array > | Function > | Variable > | '(' > Expression > ')' >
    function Value( &$result, $sub ) {
		$result['expression'] = new BooleanUnaryExpression(BooleanUnaryExpression::TYPE_NOT, $sub['expression']);
	}
    function String( &$result, $sub ) {
		$result['expression'] = new StringConstExpression($sub['val']['text']);
	}
	function Boolean( &$result, $sub ) {
		$result['expression'] = new BooleanConstExpression($sub['text']);
	}
    function Number( &$result, $sub ) {
		$result['expression'] = new ArithmeticConstExpression($sub['text']);
	}
    function Array( &$result, $sub ) {
        $result['expression'] = $sub['expression'];
    }
	function Function( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}
	function Variable( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}
	function Expression( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
Mul: '*' > second_operand:Value >
Div: '/' > second_operand:Value >
Mod: '%' > second_operand:Value >
Product: Value > ( Mul | Div | Mod )*
	function Value( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
	function Mul( &$result, $sub ) {
		$result['expression'] = new ArithmeticBinaryExpression(
			ArithmeticBinaryExpression::TYPE_MUL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}
	function Div( &$result, $sub ) {
		$result['expression'] = new ArithmeticBinaryExpression(
			ArithmeticBinaryExpression::TYPE_DIV,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}
	function Mod( &$result, $sub ) {
		$result['expression'] = new ArithmeticBinaryExpression(
			ArithmeticBinaryExpression::TYPE_MOD,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}
MinusProduct: '-' Product
	function Product( &$result, $sub ) {
		$result['expression'] = new ArithmeticUnaryExpression(
			ArithmeticUnaryExpression::TYPE_MINUS,
			$sub['expression']
		);
	}
Plus: '+' > second_operand:Product >
Minus: '-' > second_operand:Product >
Sum: ( MinusProduct | Product ) > ( Plus | Minus )*
	function MinusProduct( &$result, $sub ) {
		$result['sum'] = $sub['expression'];
	}
	function Product( &$result, $sub ) {
		$result['sum'] = $sub['expression'];
	}
	function Plus( &$result, $sub ) {
		$result['sum'] = new ArithmeticBinaryExpression(
			ArithmeticBinaryExpression::TYPE_PLUS,
			$result['sum'],
			$sub['second_operand']['expression']
		);
	}
	function Minus( &$result, $sub ) {
		$result['sum'] = new ArithmeticBinaryExpression(
			ArithmeticBinaryExpression::TYPE_MINUS,
			$result['sum'],
			$sub['second_operand']['expression']
		);
	}
ArithmeticExpression: Sum >
	function *( &$result, $sub ) {
		$result['expression'] = $sub['sum'];
	}
Greater: '>' > second_operand:ArithmeticExpression >
Less: '<' > second_operand:ArithmeticExpression >
LessOrEqual: '<=' > second_operand:ArithmeticExpression >
GreaterOrEqual: '>=' > second_operand:ArithmeticExpression >
Equal: '==' > second_operand:ArithmeticExpression >
NotEqual: '!=' > second_operand:ArithmeticExpression >
In: 'in' > second_operand:ArithmeticExpression >
CompareExpression: ArithmeticExpression > ( Greater | Less | LessOrEqual | GreaterOrEqual | Equal | NotEqual | In )?
	function ArithmeticExpression( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
	function Greater( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_GREATER,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}
	function Less( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_LESS,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}
	function LessOrEqual( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_LESS_OR_EQUAL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}
	function GreaterOrEqual( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_GREATER_OR_EQUAL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}
	function Equal( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_EQUAL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}
	function NotEqual( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_NOT_EQUAL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}
    function In( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_IN,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}
And: '&&' > second_operand:CompareExpression >
Or: '||' > second_operand:CompareExpression >
BooleanExpression: CompareExpression > ( And | Or )* >
	function CompareExpression( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
	function And( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_AND,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}
	function Or( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_OR,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

Expression: BooleanExpression
	function *( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
	
*/

}