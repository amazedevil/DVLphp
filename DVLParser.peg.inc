<?php

require_once 'vendor/autoload.php';
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
String: /'([^'\\]|\\')*'/ | /"([^"\\]|\\")*"/
Name: !Boolean !This !N /[a-zA-Z_]+([a-zA-Z0-9_]*)/
Boolean: 'true' | 'false'
This: 'this' ![a-zA-Z0-9_]
N: 'n' ![a-zA-Z0-9_]

Selector: Expression
Property: '.' Name >
ArrayElement: '[' > ( Selector )? > ']' >
Variable: ( Name | This | N ) (Property | ArrayElement)* >

Argument: Expression
Function: Name '(' > ( Argument )? > ( ',' > Argument > )* ')' >

Use: '(' > Expression > ')' > ValidationControl >
	function Expression( &$result, $sub ) {
		echo "Use - Expression: {$sub['text']}\n";
	}
	function ValidationControl( &$result, $sub ) {
		echo "Use - ValidationControl: {$sub['text']}\n";
	}
Ternary: '(' > BooleanExpression > ')' > '?' > ValidationControl > ( ':' > ValidationControl > )? >
Group: '{' > ValidationControl > ( ',' > ValidationControl > )* '}' >
	function ValidationControl( &$result, $sub ) {
		echo "ValidationControl: {$sub['text']}\n";
	}
ValidationControl: Group | Ternary | Use | Validation
	function Group( &$result, $sub ) {
		echo "Group: {$sub['text']}\n";
	}
	function Ternary( &$result, $sub ) {
		echo "Ternary: {$sub['text']}\n";
	}
	function Use( &$result, $sub ) {
		echo "Use: {$sub['text']}\n";
	}
Validation: Expression ( > '@' > String )? >
	function Use( &$result, $sub ) {
		echo "Validation - Use: {$sub['text']}\n";
	}
	function Expression( &$result, $sub ) {
		echo "Validation - Expression: {$sub['text']}\n";
	}
	function Ternary( &$result, $sub ) {
		echo "Validation - Ternary: {$sub['text']}\n";
	}

BooleanValue: Boolean > | Function > | Variable > | '(' > BooleanExpression > ')' >
EqualityComparableExpression: NumericExpression | BooleanValue | String >
Greater: '>' > NumericExpression >
Less: '<' > NumericExpression >
LessOrEqual: '<=' > NumericExpression >
GreaterOrEqual: '>=' > NumericExpression >
Equal: '==' > EqualityComparableExpression >
NotEqual: '!=' > EqualityComparableExpression >
And: '&&' > BooleanValue >
Or: '||' > BooleanValue >
Not: '!' BooleanValue >
BooleanOperation: Not | NumericExpression > ( Greater | Less | LessOrEqual | GreaterOrEqual ) | EqualityComparableExpression > ( Equal | NotEqual ) | BooleanValue > ( And | Or ) >
BooleanExpression: BooleanOperation | BooleanValue >

NumericValue: Number > | Function > | Variable > | '(' > NumericExpression > ')' >
Mul: '*' > NumericValue >
Div: '/' > NumericValue >
Mod: '%' > NumericValue >
Product: NumericValue > ( Mul | Div | Mod )*
Plus: '+' > Product >
Minus: '-' > Product >
Sum: ( '-' Product | Product ) > ( Plus | Minus )*
NumericExpression: Sum >

Expression: String > | !BooleanOperation NumericExpression | BooleanExpression
	function NumericExpression( &$result, $sub ) {
		echo "Expression - NumericExpression: {$sub['text']}\n";
	}
	function BooleanExpression( &$result, $sub ) {
		echo "Expression - BooleanExpression: {$sub['text']}\n";
	}
	function String( &$result, $sub ) {
		echo "Expression - String: {$sub['text']}\n";
	}
	
*/

}

//Debug
//$x = new DVLParser( file_get_contents('test.dvl') ) ;
$x = new DVLParser( file_get_contents('working.dvl') ) ;
//$x = new DVLParser( 'this[n % 2 > 0] == 2' );
$res = $x->match_ValidationControl();
if ( $res === FALSE ) {
	print "No Match\n" ;
}
else {
	print_r( $res ) ;
}