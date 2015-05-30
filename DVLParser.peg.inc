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

Number: /[0-9]+/
String: "\'" val:(/([^'\\]|\\')* /) "\'" | '"' val:(/([^"\\]|\\")* /) '"' 
Name: !Boolean !This /[a-zA-Z_]+([a-zA-Z0-9_]*)/
Tag: !Boolean !This /[a-zA-Z0-9_]+/
Boolean: 'true' | 'false'
This: 'this' !/[a-zA-Z0-9_]/

Property: '.' Name >
	function Name( &$result, $sub ) {
		$result['accessor'] = new PropertyAccessor($sub['text']);
	}
ArrayElement: '[' > ( selector:Expression )? > ']' >
Variable: ( Name | This ) (Property | ArrayElement)* >
	function Name( &$result, $sub ) {
		$result['variable'] = new VariableExpression([ new VariableAccessor($sub['text']) ]);
	}
	function This( &$result, $sub ) {
		$result['variable'] = new VariableExpression([ new ThisAccessor() ]);
	}
	function Property( &$result, $sub ) {
		$result['variable']->addAccessor($sub['accessor']);
	}
	function ArrayElement( &$result, $sub ) {
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
Ternary: '(' > BooleanExpression > ')' > '?' > ValidationControl > ( ':' > ValidationControl > )?
	function BooleanExpression( &$result, $sub ) {
		$result['validation'] = new TernaryValidation( $sub['expression'] );
	}
	function ValidationControl( &$result, $sub ) {
		if (!$result['validation']->hasPositive()) {
			$result['validation']->setPositive( $sub['validation'] );
		} else {
			$result['validation']->setNegative( $sub['validation'] );
		}
	}
Foreach: '$(' > ArrayExpression > ( ':' > Name > ( '=>' > Name )? )? > ')' > ValidationControl >
	function ArrayExpression( &$result, $sub ) {
		$result['validation'] = new ForeachValidation([ $sub['validation'] ]);
	}
	function Name( &$result, $sub ) {
		if ($result['validation']->valueName === null) {
			$result['validation']->valueName = $sub['text'];
		} else {
			$result['validation']->keyName = $result['validation']->valueName;
			$result['validation']->valueName = $sub['text'];
		}
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
ValidationControl: Group | Ternary | Use | Validation
	function *( &$result, $sub ) {
		$result['validation'] = $sub['validation'];
	}
Validation: Expression ( > '@' > String > ( ':' > Tag )? )? >
	function Expression( &$result, $sub ) {
		$result['validation'] = new Validation( $sub['expression'] );
	}
	function String( &$result, $sub ) {
		$result['validation']->setMessage($sub['val']['text']);
	}
	function Tag( &$result, $sub ) {
		echo "Validation - Tag\n";
		$result['validation']->setTag($sub['text']);
	}

BooleanValue: Boolean > | Function > | Variable > | '(' > BooleanExpression > ')' >
	function Boolean( &$result, $sub ) {
		$result['expression'] = new BooleanConstExpression($sub['text']);
	}
	function Function( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}
	function Variable( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}
	function BooleanExpression( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
EqualityComparableExpression: NumericExpression | BooleanValue | StringExpression >
	function *( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
Greater: '>' > second_operand:NumericExpression >
Less: '<' > second_operand:NumericExpression >
LessOrEqual: '<=' > second_operand:NumericExpression >
GreaterOrEqual: '>=' > second_operand:NumericExpression >
Equal: '==' > second_operand:EqualityComparableExpression >
NotEqual: '!=' > second_operand:EqualityComparableExpression >
And: '&&' > second_operand:BooleanValue >
Or: '||' > second_operand:BooleanValue >
Not: '!' BooleanValue >
	function BooleanValue( &$result, $sub ) {
		$result['expression'] = new BooleanUnaryExpression(BooleanUnaryExpression::TYPE_NOT, $sub['expression']);
	}
BooleanBinaryOperatorSign: '>' | '<' | '>=' | '<=' | '==' | '!=' | '&&' | '||'
BooleanOperation: Not | NumericExpression > ( Greater | Less | LessOrEqual | GreaterOrEqual ) | EqualityComparableExpression > ( Equal | NotEqual ) | BooleanValue > ( And | Or ) >
	function Not( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
	function NumericExpression( &$result, $sub ) {
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
	function EqualityComparableExpression( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
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
	function BooleanValue( &$result, $sub ) {
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
BooleanExpression: BooleanOperation | BooleanValue >
	function *( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
	
NumericValue: Number > | Function > | Variable > | '(' > NumericExpression > ')' >
	function Number( &$result, $sub ) {
		$result['expression'] = new ArithmeticConstExpression($sub['text']);
	}
	function Function( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}
	function Variable( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}
	function NumericExpression( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
Mul: '*' > second_operand:NumericValue >
Div: '/' > second_operand:NumericValue >
Mod: '%' > second_operand:NumericValue >
Product: NumericValue > ( Mul | Div | Mod )*
	function NumericValue( &$result, $sub ) {
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
			$sub['operand']['expression']
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
NumericExpression: Sum >
	function Sum( &$result, $sub ) {
		$result['expression'] = $sub['sum'];
	}
	
StringExpression: String
	function String( &$result, $sub ) {
		$result['expression'] = new StringConstExpression($sub['val']['text']);
	}

Expression: StringExpression > | NumericExpression !BooleanBinaryOperatorSign | BooleanExpression
	function NumericExpression( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
	function BooleanExpression( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
	function StringExpression( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}
	
ArrayExpression: Function > | Variable >
	function Function( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}
	function Variable( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}
	
*/

}