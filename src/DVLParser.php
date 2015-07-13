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

/* Number: /[0-9]+(\.[0-9]+)* / */
protected $match_Number_typestack = array('Number');
function match_Number ($stack = array()) {
	$matchrule = "Number"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->rx( '/[0-9]+(\.[0-9]+)* /' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* String: "\'" val:(/([^'\\]|\\'|\\\\)* /) "\'" | '"' val:(/([^"\\]|\\"|\\\\)* /) '"'  */
protected $match_String_typestack = array('String');
function match_String ($stack = array()) {
	$matchrule = "String"; $result = $this->construct($matchrule, $matchrule, null);
	$_16 = NULL;
	do {
		$res_1 = $result;
		$pos_1 = $this->pos;
		$_7 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == '\'') {
				$this->pos += 1;
				$result["text"] .= '\'';
			}
			else { $_7 = FALSE; break; }
			$stack[] = $result; $result = $this->construct( $matchrule, "val" ); 
			$_4 = NULL;
			do {
				if (( $subres = $this->rx( '/([^\'\\\\]|\\\\\'|\\\\\\\\)* /' ) ) !== FALSE) { $result["text"] .= $subres; }
				else { $_4 = FALSE; break; }
				$_4 = TRUE; break;
			}
			while(0);
			if( $_4 === TRUE ) {
				$subres = $result; $result = array_pop($stack);
				$this->store( $result, $subres, 'val' );
			}
			if( $_4 === FALSE) {
				$result = array_pop($stack);
				$_7 = FALSE; break;
			}
			if (substr($this->string,$this->pos,1) == '\'') {
				$this->pos += 1;
				$result["text"] .= '\'';
			}
			else { $_7 = FALSE; break; }
			$_7 = TRUE; break;
		}
		while(0);
		if( $_7 === TRUE ) { $_16 = TRUE; break; }
		$result = $res_1;
		$this->pos = $pos_1;
		$_14 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == '"') {
				$this->pos += 1;
				$result["text"] .= '"';
			}
			else { $_14 = FALSE; break; }
			$stack[] = $result; $result = $this->construct( $matchrule, "val" ); 
			$_11 = NULL;
			do {
				if (( $subres = $this->rx( '/([^"\\\\]|\\\\"|\\\\\\\\)* /' ) ) !== FALSE) { $result["text"] .= $subres; }
				else { $_11 = FALSE; break; }
				$_11 = TRUE; break;
			}
			while(0);
			if( $_11 === TRUE ) {
				$subres = $result; $result = array_pop($stack);
				$this->store( $result, $subres, 'val' );
			}
			if( $_11 === FALSE) {
				$result = array_pop($stack);
				$_14 = FALSE; break;
			}
			if (substr($this->string,$this->pos,1) == '"') {
				$this->pos += 1;
				$result["text"] .= '"';
			}
			else { $_14 = FALSE; break; }
			$_14 = TRUE; break;
		}
		while(0);
		if( $_14 === TRUE ) { $_16 = TRUE; break; }
		$result = $res_1;
		$this->pos = $pos_1;
		$_16 = FALSE; break;
	}
	while(0);
	if( $_16 === TRUE ) { return $this->finalise($result); }
	if( $_16 === FALSE) { return FALSE; }
}


/* Name: !Boolean !This /[a-zA-Z_]+([a-zA-Z0-9_]*)/ */
protected $match_Name_typestack = array('Name');
function match_Name ($stack = array()) {
	$matchrule = "Name"; $result = $this->construct($matchrule, $matchrule, null);
	$_21 = NULL;
	do {
		$res_18 = $result;
		$pos_18 = $this->pos;
		$matcher = 'match_'.'Boolean'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$result = $res_18;
			$this->pos = $pos_18;
			$_21 = FALSE; break;
		}
		else {
			$result = $res_18;
			$this->pos = $pos_18;
		}
		$res_19 = $result;
		$pos_19 = $this->pos;
		$matcher = 'match_'.'This'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$result = $res_19;
			$this->pos = $pos_19;
			$_21 = FALSE; break;
		}
		else {
			$result = $res_19;
			$this->pos = $pos_19;
		}
		if (( $subres = $this->rx( '/[a-zA-Z_]+([a-zA-Z0-9_]*)/' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_21 = FALSE; break; }
		$_21 = TRUE; break;
	}
	while(0);
	if( $_21 === TRUE ) { return $this->finalise($result); }
	if( $_21 === FALSE) { return FALSE; }
}


/* Boolean: 'true' | 'false' */
protected $match_Boolean_typestack = array('Boolean');
function match_Boolean ($stack = array()) {
	$matchrule = "Boolean"; $result = $this->construct($matchrule, $matchrule, null);
	$_26 = NULL;
	do {
		$res_23 = $result;
		$pos_23 = $this->pos;
		if (( $subres = $this->literal( 'true' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$_26 = TRUE; break;
		}
		$result = $res_23;
		$this->pos = $pos_23;
		if (( $subres = $this->literal( 'false' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$_26 = TRUE; break;
		}
		$result = $res_23;
		$this->pos = $pos_23;
		$_26 = FALSE; break;
	}
	while(0);
	if( $_26 === TRUE ) { return $this->finalise($result); }
	if( $_26 === FALSE) { return FALSE; }
}


/* This: 'this' !/[a-zA-Z0-9_]/ */
protected $match_This_typestack = array('This');
function match_This ($stack = array()) {
	$matchrule = "This"; $result = $this->construct($matchrule, $matchrule, null);
	$_30 = NULL;
	do {
		if (( $subres = $this->literal( 'this' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_30 = FALSE; break; }
		$res_29 = $result;
		$pos_29 = $this->pos;
		if (( $subres = $this->rx( '/[a-zA-Z0-9_]/' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$result = $res_29;
			$this->pos = $pos_29;
			$_30 = FALSE; break;
		}
		else {
			$result = $res_29;
			$this->pos = $pos_29;
		}
		$_30 = TRUE; break;
	}
	while(0);
	if( $_30 === TRUE ) { return $this->finalise($result); }
	if( $_30 === FALSE) { return FALSE; }
}


/* Array: '[' > initial:Expression > ( ',' > additional:Expression > )* ']' */
protected $match_Array_typestack = array('Array');
function match_Array ($stack = array()) {
	$matchrule = "Array"; $result = $this->construct($matchrule, $matchrule, null);
	$_43 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '[') {
			$this->pos += 1;
			$result["text"] .= '[';
		}
		else { $_43 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "initial" );
		}
		else { $_43 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_41 = $result;
			$pos_41 = $this->pos;
			$_40 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_40 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres, "additional" );
				}
				else { $_40 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_40 = TRUE; break;
			}
			while(0);
			if( $_40 === FALSE) {
				$result = $res_41;
				$this->pos = $pos_41;
				unset( $res_41 );
				unset( $pos_41 );
				break;
			}
		}
		if (substr($this->string,$this->pos,1) == ']') {
			$this->pos += 1;
			$result["text"] .= ']';
		}
		else { $_43 = FALSE; break; }
		$_43 = TRUE; break;
	}
	while(0);
	if( $_43 === TRUE ) { return $this->finalise($result); }
	if( $_43 === FALSE) { return FALSE; }
}

public function Array_initial ( &$result, $sub ) {
		$result['expression'] = new ArrayConstExpression([ $sub['expression'] ]);
	}

public function Array_additional ( &$result, $sub ) {
        $result['expression']->addExpression( $sub['expression'] );
    }

/* PropertySelector: '.' Name > */
protected $match_PropertySelector_typestack = array('PropertySelector');
function match_PropertySelector ($stack = array()) {
	$matchrule = "PropertySelector"; $result = $this->construct($matchrule, $matchrule, null);
	$_48 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '.') {
			$this->pos += 1;
			$result["text"] .= '.';
		}
		else { $_48 = FALSE; break; }
		$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_48 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_48 = TRUE; break;
	}
	while(0);
	if( $_48 === TRUE ) { return $this->finalise($result); }
	if( $_48 === FALSE) { return FALSE; }
}

public function PropertySelector_Name ( &$result, $sub ) {
		$result['accessor'] = new PropertyAccessor($sub['text']);
	}

/* ArrayElementSelector: '[' > selector:Expression > ']' > */
protected $match_ArrayElementSelector_typestack = array('ArrayElementSelector');
function match_ArrayElementSelector ($stack = array()) {
	$matchrule = "ArrayElementSelector"; $result = $this->construct($matchrule, $matchrule, null);
	$_56 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '[') {
			$this->pos += 1;
			$result["text"] .= '[';
		}
		else { $_56 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "selector" );
		}
		else { $_56 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ']') {
			$this->pos += 1;
			$result["text"] .= ']';
		}
		else { $_56 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_56 = TRUE; break;
	}
	while(0);
	if( $_56 === TRUE ) { return $this->finalise($result); }
	if( $_56 === FALSE) { return FALSE; }
}


/* Variable: ( Name | This ) (PropertySelector | ArrayElementSelector)* > */
protected $match_Variable_typestack = array('Variable');
function match_Variable ($stack = array()) {
	$matchrule = "Variable"; $result = $this->construct($matchrule, $matchrule, null);
	$_73 = NULL;
	do {
		$_63 = NULL;
		do {
			$_61 = NULL;
			do {
				$res_58 = $result;
				$pos_58 = $this->pos;
				$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_61 = TRUE; break;
				}
				$result = $res_58;
				$this->pos = $pos_58;
				$matcher = 'match_'.'This'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_61 = TRUE; break;
				}
				$result = $res_58;
				$this->pos = $pos_58;
				$_61 = FALSE; break;
			}
			while(0);
			if( $_61 === FALSE) { $_63 = FALSE; break; }
			$_63 = TRUE; break;
		}
		while(0);
		if( $_63 === FALSE) { $_73 = FALSE; break; }
		while (true) {
			$res_71 = $result;
			$pos_71 = $this->pos;
			$_70 = NULL;
			do {
				$_68 = NULL;
				do {
					$res_65 = $result;
					$pos_65 = $this->pos;
					$matcher = 'match_'.'PropertySelector'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_68 = TRUE; break;
					}
					$result = $res_65;
					$this->pos = $pos_65;
					$matcher = 'match_'.'ArrayElementSelector'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_68 = TRUE; break;
					}
					$result = $res_65;
					$this->pos = $pos_65;
					$_68 = FALSE; break;
				}
				while(0);
				if( $_68 === FALSE) { $_70 = FALSE; break; }
				$_70 = TRUE; break;
			}
			while(0);
			if( $_70 === FALSE) {
				$result = $res_71;
				$this->pos = $pos_71;
				unset( $res_71 );
				unset( $pos_71 );
				break;
			}
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_73 = TRUE; break;
	}
	while(0);
	if( $_73 === TRUE ) { return $this->finalise($result); }
	if( $_73 === FALSE) { return FALSE; }
}

public function Variable_Name ( &$result, $sub ) {
		$result['variable'] = new VariableExpression([ new VariableAccessor($sub['text']) ]);
	}

public function Variable_This ( &$result, $sub ) {
		$result['variable'] = new VariableExpression([ new ThisAccessor() ]);
	}

public function Variable_PropertySelector ( &$result, $sub ) {
		$result['variable']->addAccessor($sub['accessor']);
	}

public function Variable_ArrayElementSelector ( &$result, $sub ) {
		$result['variable']->addAccessor(new CollectionAccessor(
			isset($sub['selector']['expression']) ? $sub['selector']['expression'] : null
		));
	}

/* Function: Name '(' > ( Expression )? > ( ',' > Expression > )* ')' > */
protected $match_Function_typestack = array('Function');
function match_Function ($stack = array()) {
	$matchrule = "Function"; $result = $this->construct($matchrule, $matchrule, null);
	$_90 = NULL;
	do {
		$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_90 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_90 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_80 = $result;
		$pos_80 = $this->pos;
		$_79 = NULL;
		do {
			$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_79 = FALSE; break; }
			$_79 = TRUE; break;
		}
		while(0);
		if( $_79 === FALSE) {
			$result = $res_80;
			$this->pos = $pos_80;
			unset( $res_80 );
			unset( $pos_80 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_87 = $result;
			$pos_87 = $this->pos;
			$_86 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_86 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_86 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_86 = TRUE; break;
			}
			while(0);
			if( $_86 === FALSE) {
				$result = $res_87;
				$this->pos = $pos_87;
				unset( $res_87 );
				unset( $pos_87 );
				break;
			}
		}
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_90 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_90 = TRUE; break;
	}
	while(0);
	if( $_90 === TRUE ) { return $this->finalise($result); }
	if( $_90 === FALSE) { return FALSE; }
}

public function Function_Name ( &$result, $sub ) {
		$result['function'] = new FunctionExpression( $sub['text'] );
	}

public function Function_Expression ( &$result, $sub ) {
		$result['function']->addArgument($sub['expression']);
	}

/* Use: '(' > Expression > ')' > ValidationControl > */
protected $match_Use_typestack = array('Use');
function match_Use ($stack = array()) {
	$matchrule = "Use"; $result = $this->construct($matchrule, $matchrule, null);
	$_100 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_100 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_100 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_100 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_100 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_100 = TRUE; break;
	}
	while(0);
	if( $_100 === TRUE ) { return $this->finalise($result); }
	if( $_100 === FALSE) { return FALSE; }
}

public function Use_Expression ( &$result, $sub ) {
		$result['validation'] = new UseValidation( $sub['expression'] );
	}

public function Use_ValidationControl ( &$result, $sub ) {
		$result['validation']->setValidation( $sub['validation'] );
	}

/* Ternary: '(' > Expression > ')' > '?' > ValidationControl > ( ':' > ValidationControl > )? */
protected $match_Ternary_typestack = array('Ternary');
function match_Ternary ($stack = array()) {
	$matchrule = "Ternary"; $result = $this->construct($matchrule, $matchrule, null);
	$_118 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_118 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_118 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_118 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == '?') {
			$this->pos += 1;
			$result["text"] .= '?';
		}
		else { $_118 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_118 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_117 = $result;
		$pos_117 = $this->pos;
		$_116 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == ':') {
				$this->pos += 1;
				$result["text"] .= ':';
			}
			else { $_116 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_116 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_116 = TRUE; break;
		}
		while(0);
		if( $_116 === FALSE) {
			$result = $res_117;
			$this->pos = $pos_117;
			unset( $res_117 );
			unset( $pos_117 );
		}
		$_118 = TRUE; break;
	}
	while(0);
	if( $_118 === TRUE ) { return $this->finalise($result); }
	if( $_118 === FALSE) { return FALSE; }
}

public function Ternary_Expression ( &$result, $sub ) {
		$result['validation'] = new TernaryValidation( $sub['expression'] );
	}

public function Ternary_ValidationControl ( &$result, $sub ) {
		if (!$result['validation']->hasPositive()) {
			$result['validation']->setPositive( $sub['validation'] );
		} else {
			$result['validation']->setNegative( $sub['validation'] );
		}
	}

/* Foreach: '$(' > Expression > ( ':' > key_value:Name > ( '=>' > value:Name )? )? > ')' > ValidationControl > */
protected $match_Foreach_typestack = array('Foreach');
function match_Foreach ($stack = array()) {
	$matchrule = "Foreach"; $result = $this->construct($matchrule, $matchrule, null);
	$_140 = NULL;
	do {
		if (( $subres = $this->literal( '$(' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_140 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_140 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_134 = $result;
		$pos_134 = $this->pos;
		$_133 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == ':') {
				$this->pos += 1;
				$result["text"] .= ':';
			}
			else { $_133 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres, "key_value" );
			}
			else { $_133 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$res_132 = $result;
			$pos_132 = $this->pos;
			$_131 = NULL;
			do {
				if (( $subres = $this->literal( '=>' ) ) !== FALSE) { $result["text"] .= $subres; }
				else { $_131 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres, "value" );
				}
				else { $_131 = FALSE; break; }
				$_131 = TRUE; break;
			}
			while(0);
			if( $_131 === FALSE) {
				$result = $res_132;
				$this->pos = $pos_132;
				unset( $res_132 );
				unset( $pos_132 );
			}
			$_133 = TRUE; break;
		}
		while(0);
		if( $_133 === FALSE) {
			$result = $res_134;
			$this->pos = $pos_134;
			unset( $res_134 );
			unset( $pos_134 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_140 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_140 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_140 = TRUE; break;
	}
	while(0);
	if( $_140 === TRUE ) { return $this->finalise($result); }
	if( $_140 === FALSE) { return FALSE; }
}

public function Foreach_Expression ( &$result, $sub ) {
		$result['validation'] = new ForeachValidation( $sub['expression'] );
	}

public function Foreach_key_value ( &$result, $sub ) {
		$result['validation']->valueName = $sub['text'];
	}

public function Foreach_value ( &$result, $sub ) {
		$result['validation']->keyName = $result['validation']->valueName;
		$result['validation']->valueName = $sub['text'];
	}

public function Foreach_ValidationControl ( &$result, $sub ) {
		$result['validation']->setValidation( $sub['validation'] );
	}

/* Group: '{' > ValidationControl > ( ',' > ValidationControl > )* '}' > */
protected $match_Group_typestack = array('Group');
function match_Group ($stack = array()) {
	$matchrule = "Group"; $result = $this->construct($matchrule, $matchrule, null);
	$_154 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '{') {
			$this->pos += 1;
			$result["text"] .= '{';
		}
		else { $_154 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_154 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_151 = $result;
			$pos_151 = $this->pos;
			$_150 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_150 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_150 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_150 = TRUE; break;
			}
			while(0);
			if( $_150 === FALSE) {
				$result = $res_151;
				$this->pos = $pos_151;
				unset( $res_151 );
				unset( $pos_151 );
				break;
			}
		}
		if (substr($this->string,$this->pos,1) == '}') {
			$this->pos += 1;
			$result["text"] .= '}';
		}
		else { $_154 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_154 = TRUE; break;
	}
	while(0);
	if( $_154 === TRUE ) { return $this->finalise($result); }
	if( $_154 === FALSE) { return FALSE; }
}

public function Group_ValidationControl ( &$result, $sub ) {
		if (!isset($result['validation'])) {
			$result['validation'] = new GroupValidation([ $sub['validation'] ]);
		} else {
			$result['validation']->addValidation( $sub['validation'] );
		}
	}

/* ValidationControl: Group | Foreach | Ternary | Use | Validation */
protected $match_ValidationControl_typestack = array('ValidationControl');
function match_ValidationControl ($stack = array()) {
	$matchrule = "ValidationControl"; $result = $this->construct($matchrule, $matchrule, null);
	$_171 = NULL;
	do {
		$res_156 = $result;
		$pos_156 = $this->pos;
		$matcher = 'match_'.'Group'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_171 = TRUE; break;
		}
		$result = $res_156;
		$this->pos = $pos_156;
		$_169 = NULL;
		do {
			$res_158 = $result;
			$pos_158 = $this->pos;
			$matcher = 'match_'.'Foreach'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_169 = TRUE; break;
			}
			$result = $res_158;
			$this->pos = $pos_158;
			$_167 = NULL;
			do {
				$res_160 = $result;
				$pos_160 = $this->pos;
				$matcher = 'match_'.'Ternary'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_167 = TRUE; break;
				}
				$result = $res_160;
				$this->pos = $pos_160;
				$_165 = NULL;
				do {
					$res_162 = $result;
					$pos_162 = $this->pos;
					$matcher = 'match_'.'Use'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_165 = TRUE; break;
					}
					$result = $res_162;
					$this->pos = $pos_162;
					$matcher = 'match_'.'Validation'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_165 = TRUE; break;
					}
					$result = $res_162;
					$this->pos = $pos_162;
					$_165 = FALSE; break;
				}
				while(0);
				if( $_165 === TRUE ) { $_167 = TRUE; break; }
				$result = $res_160;
				$this->pos = $pos_160;
				$_167 = FALSE; break;
			}
			while(0);
			if( $_167 === TRUE ) { $_169 = TRUE; break; }
			$result = $res_158;
			$this->pos = $pos_158;
			$_169 = FALSE; break;
		}
		while(0);
		if( $_169 === TRUE ) { $_171 = TRUE; break; }
		$result = $res_156;
		$this->pos = $pos_156;
		$_171 = FALSE; break;
	}
	while(0);
	if( $_171 === TRUE ) { return $this->finalise($result); }
	if( $_171 === FALSE) { return FALSE; }
}

public function ValidationControl_STR ( &$result, $sub ) {
		$result['validation'] = $sub['validation'];
	}

/* Validation: Expression ( > '@' > message:String > ( '%' > tag:String )? )? > */
protected $match_Validation_typestack = array('Validation');
function match_Validation ($stack = array()) {
	$matchrule = "Validation"; $result = $this->construct($matchrule, $matchrule, null);
	$_187 = NULL;
	do {
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_187 = FALSE; break; }
		$res_185 = $result;
		$pos_185 = $this->pos;
		$_184 = NULL;
		do {
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			if (substr($this->string,$this->pos,1) == '@') {
				$this->pos += 1;
				$result["text"] .= '@';
			}
			else { $_184 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres, "message" );
			}
			else { $_184 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$res_183 = $result;
			$pos_183 = $this->pos;
			$_182 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == '%') {
					$this->pos += 1;
					$result["text"] .= '%';
				}
				else { $_182 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres, "tag" );
				}
				else { $_182 = FALSE; break; }
				$_182 = TRUE; break;
			}
			while(0);
			if( $_182 === FALSE) {
				$result = $res_183;
				$this->pos = $pos_183;
				unset( $res_183 );
				unset( $pos_183 );
			}
			$_184 = TRUE; break;
		}
		while(0);
		if( $_184 === FALSE) {
			$result = $res_185;
			$this->pos = $pos_185;
			unset( $res_185 );
			unset( $pos_185 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_187 = TRUE; break;
	}
	while(0);
	if( $_187 === TRUE ) { return $this->finalise($result); }
	if( $_187 === FALSE) { return FALSE; }
}

public function Validation_Expression ( &$result, $sub ) {
		$result['validation'] = new Validation( $sub['expression'] );
	}

public function Validation_message ( &$result, $sub ) {
		$result['validation']->setMessageExpression(new StringConstExpression($sub['val']['text']));
	}

public function Validation_tag ( &$result, $sub ) {
		$result['validation']->setTagExpression(new StringConstExpression($sub['val']['text']));
	}

/* Value: '!' Value > | String > | Boolean > | Number > | Array > | Function > | Variable > | '(' > Expression > ')' > */
protected $match_Value_typestack = array('Value');
function match_Value ($stack = array()) {
	$matchrule = "Value"; $result = $this->construct($matchrule, $matchrule, null);
	$_245 = NULL;
	do {
		$res_189 = $result;
		$pos_189 = $this->pos;
		$_193 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == '!') {
				$this->pos += 1;
				$result["text"] .= '!';
			}
			else { $_193 = FALSE; break; }
			$matcher = 'match_'.'Value'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_193 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_193 = TRUE; break;
		}
		while(0);
		if( $_193 === TRUE ) { $_245 = TRUE; break; }
		$result = $res_189;
		$this->pos = $pos_189;
		$_243 = NULL;
		do {
			$res_195 = $result;
			$pos_195 = $this->pos;
			$_198 = NULL;
			do {
				$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_198 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_198 = TRUE; break;
			}
			while(0);
			if( $_198 === TRUE ) { $_243 = TRUE; break; }
			$result = $res_195;
			$this->pos = $pos_195;
			$_241 = NULL;
			do {
				$res_200 = $result;
				$pos_200 = $this->pos;
				$_203 = NULL;
				do {
					$matcher = 'match_'.'Boolean'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_203 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_203 = TRUE; break;
				}
				while(0);
				if( $_203 === TRUE ) { $_241 = TRUE; break; }
				$result = $res_200;
				$this->pos = $pos_200;
				$_239 = NULL;
				do {
					$res_205 = $result;
					$pos_205 = $this->pos;
					$_208 = NULL;
					do {
						$matcher = 'match_'.'Number'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
						}
						else { $_208 = FALSE; break; }
						if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
						$_208 = TRUE; break;
					}
					while(0);
					if( $_208 === TRUE ) { $_239 = TRUE; break; }
					$result = $res_205;
					$this->pos = $pos_205;
					$_237 = NULL;
					do {
						$res_210 = $result;
						$pos_210 = $this->pos;
						$_213 = NULL;
						do {
							$matcher = 'match_'.'Array'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
							}
							else { $_213 = FALSE; break; }
							if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
							$_213 = TRUE; break;
						}
						while(0);
						if( $_213 === TRUE ) { $_237 = TRUE; break; }
						$result = $res_210;
						$this->pos = $pos_210;
						$_235 = NULL;
						do {
							$res_215 = $result;
							$pos_215 = $this->pos;
							$_218 = NULL;
							do {
								$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
								}
								else { $_218 = FALSE; break; }
								if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
								$_218 = TRUE; break;
							}
							while(0);
							if( $_218 === TRUE ) { $_235 = TRUE; break; }
							$result = $res_215;
							$this->pos = $pos_215;
							$_233 = NULL;
							do {
								$res_220 = $result;
								$pos_220 = $this->pos;
								$_223 = NULL;
								do {
									$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
									$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
									if ($subres !== FALSE) {
										$this->store( $result, $subres );
									}
									else { $_223 = FALSE; break; }
									if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
									$_223 = TRUE; break;
								}
								while(0);
								if( $_223 === TRUE ) { $_233 = TRUE; break; }
								$result = $res_220;
								$this->pos = $pos_220;
								$_231 = NULL;
								do {
									if (substr($this->string,$this->pos,1) == '(') {
										$this->pos += 1;
										$result["text"] .= '(';
									}
									else { $_231 = FALSE; break; }
									if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
									$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
									$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
									if ($subres !== FALSE) {
										$this->store( $result, $subres );
									}
									else { $_231 = FALSE; break; }
									if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
									if (substr($this->string,$this->pos,1) == ')') {
										$this->pos += 1;
										$result["text"] .= ')';
									}
									else { $_231 = FALSE; break; }
									if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
									$_231 = TRUE; break;
								}
								while(0);
								if( $_231 === TRUE ) { $_233 = TRUE; break; }
								$result = $res_220;
								$this->pos = $pos_220;
								$_233 = FALSE; break;
							}
							while(0);
							if( $_233 === TRUE ) { $_235 = TRUE; break; }
							$result = $res_215;
							$this->pos = $pos_215;
							$_235 = FALSE; break;
						}
						while(0);
						if( $_235 === TRUE ) { $_237 = TRUE; break; }
						$result = $res_210;
						$this->pos = $pos_210;
						$_237 = FALSE; break;
					}
					while(0);
					if( $_237 === TRUE ) { $_239 = TRUE; break; }
					$result = $res_205;
					$this->pos = $pos_205;
					$_239 = FALSE; break;
				}
				while(0);
				if( $_239 === TRUE ) { $_241 = TRUE; break; }
				$result = $res_200;
				$this->pos = $pos_200;
				$_241 = FALSE; break;
			}
			while(0);
			if( $_241 === TRUE ) { $_243 = TRUE; break; }
			$result = $res_195;
			$this->pos = $pos_195;
			$_243 = FALSE; break;
		}
		while(0);
		if( $_243 === TRUE ) { $_245 = TRUE; break; }
		$result = $res_189;
		$this->pos = $pos_189;
		$_245 = FALSE; break;
	}
	while(0);
	if( $_245 === TRUE ) { return $this->finalise($result); }
	if( $_245 === FALSE) { return FALSE; }
}

public function Value_Value ( &$result, $sub ) {
		$result['expression'] = new BooleanUnaryExpression(BooleanUnaryExpression::TYPE_NOT, $sub['expression']);
	}

public function Value_String ( &$result, $sub ) {
		$result['expression'] = new StringConstExpression($sub['val']['text']);
	}

public function Value_Boolean ( &$result, $sub ) {
		$result['expression'] = new BooleanConstExpression($sub['text']);
	}

public function Value_Number ( &$result, $sub ) {
		$result['expression'] = new ArithmeticConstExpression($sub['text']);
	}

public function Value_Array ( &$result, $sub ) {
        $result['expression'] = $sub['expression'];
    }

public function Value_Function ( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}

public function Value_Variable ( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}

public function Value_Expression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* Mul: '*' > second_operand:Value > */
protected $match_Mul_typestack = array('Mul');
function match_Mul ($stack = array()) {
	$matchrule = "Mul"; $result = $this->construct($matchrule, $matchrule, null);
	$_251 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '*') {
			$this->pos += 1;
			$result["text"] .= '*';
		}
		else { $_251 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Value'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_251 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_251 = TRUE; break;
	}
	while(0);
	if( $_251 === TRUE ) { return $this->finalise($result); }
	if( $_251 === FALSE) { return FALSE; }
}


/* Div: '/' > second_operand:Value > */
protected $match_Div_typestack = array('Div');
function match_Div ($stack = array()) {
	$matchrule = "Div"; $result = $this->construct($matchrule, $matchrule, null);
	$_257 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '/') {
			$this->pos += 1;
			$result["text"] .= '/';
		}
		else { $_257 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Value'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_257 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_257 = TRUE; break;
	}
	while(0);
	if( $_257 === TRUE ) { return $this->finalise($result); }
	if( $_257 === FALSE) { return FALSE; }
}


/* Mod: '%' > second_operand:Value > */
protected $match_Mod_typestack = array('Mod');
function match_Mod ($stack = array()) {
	$matchrule = "Mod"; $result = $this->construct($matchrule, $matchrule, null);
	$_263 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '%') {
			$this->pos += 1;
			$result["text"] .= '%';
		}
		else { $_263 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Value'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_263 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_263 = TRUE; break;
	}
	while(0);
	if( $_263 === TRUE ) { return $this->finalise($result); }
	if( $_263 === FALSE) { return FALSE; }
}


/* Product: Value > ( Mul | Div | Mod )* */
protected $match_Product_typestack = array('Product');
function match_Product ($stack = array()) {
	$matchrule = "Product"; $result = $this->construct($matchrule, $matchrule, null);
	$_278 = NULL;
	do {
		$matcher = 'match_'.'Value'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_278 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_277 = $result;
			$pos_277 = $this->pos;
			$_276 = NULL;
			do {
				$_274 = NULL;
				do {
					$res_267 = $result;
					$pos_267 = $this->pos;
					$matcher = 'match_'.'Mul'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_274 = TRUE; break;
					}
					$result = $res_267;
					$this->pos = $pos_267;
					$_272 = NULL;
					do {
						$res_269 = $result;
						$pos_269 = $this->pos;
						$matcher = 'match_'.'Div'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_272 = TRUE; break;
						}
						$result = $res_269;
						$this->pos = $pos_269;
						$matcher = 'match_'.'Mod'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_272 = TRUE; break;
						}
						$result = $res_269;
						$this->pos = $pos_269;
						$_272 = FALSE; break;
					}
					while(0);
					if( $_272 === TRUE ) { $_274 = TRUE; break; }
					$result = $res_267;
					$this->pos = $pos_267;
					$_274 = FALSE; break;
				}
				while(0);
				if( $_274 === FALSE) { $_276 = FALSE; break; }
				$_276 = TRUE; break;
			}
			while(0);
			if( $_276 === FALSE) {
				$result = $res_277;
				$this->pos = $pos_277;
				unset( $res_277 );
				unset( $pos_277 );
				break;
			}
		}
		$_278 = TRUE; break;
	}
	while(0);
	if( $_278 === TRUE ) { return $this->finalise($result); }
	if( $_278 === FALSE) { return FALSE; }
}

public function Product_Value ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

public function Product_Mul ( &$result, $sub ) {
		$result['expression'] = new ArithmeticBinaryExpression(
			ArithmeticBinaryExpression::TYPE_MUL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function Product_Div ( &$result, $sub ) {
		$result['expression'] = new ArithmeticBinaryExpression(
			ArithmeticBinaryExpression::TYPE_DIV,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function Product_Mod ( &$result, $sub ) {
		$result['expression'] = new ArithmeticBinaryExpression(
			ArithmeticBinaryExpression::TYPE_MOD,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

/* MinusProduct: '-' Product */
protected $match_MinusProduct_typestack = array('MinusProduct');
function match_MinusProduct ($stack = array()) {
	$matchrule = "MinusProduct"; $result = $this->construct($matchrule, $matchrule, null);
	$_282 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_282 = FALSE; break; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_282 = FALSE; break; }
		$_282 = TRUE; break;
	}
	while(0);
	if( $_282 === TRUE ) { return $this->finalise($result); }
	if( $_282 === FALSE) { return FALSE; }
}

public function MinusProduct_Product ( &$result, $sub ) {
		$result['expression'] = new ArithmeticUnaryExpression(
			ArithmeticUnaryExpression::TYPE_MINUS,
			$sub['expression']
		);
	}

/* Plus: '+' > second_operand:Product > */
protected $match_Plus_typestack = array('Plus');
function match_Plus ($stack = array()) {
	$matchrule = "Plus"; $result = $this->construct($matchrule, $matchrule, null);
	$_288 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '+') {
			$this->pos += 1;
			$result["text"] .= '+';
		}
		else { $_288 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_288 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_288 = TRUE; break;
	}
	while(0);
	if( $_288 === TRUE ) { return $this->finalise($result); }
	if( $_288 === FALSE) { return FALSE; }
}


/* Minus: '-' > second_operand:Product > */
protected $match_Minus_typestack = array('Minus');
function match_Minus ($stack = array()) {
	$matchrule = "Minus"; $result = $this->construct($matchrule, $matchrule, null);
	$_294 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_294 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_294 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_294 = TRUE; break;
	}
	while(0);
	if( $_294 === TRUE ) { return $this->finalise($result); }
	if( $_294 === FALSE) { return FALSE; }
}


/* Sum: ( MinusProduct | Product ) > ( Plus | Minus )* */
protected $match_Sum_typestack = array('Sum');
function match_Sum ($stack = array()) {
	$matchrule = "Sum"; $result = $this->construct($matchrule, $matchrule, null);
	$_311 = NULL;
	do {
		$_301 = NULL;
		do {
			$_299 = NULL;
			do {
				$res_296 = $result;
				$pos_296 = $this->pos;
				$matcher = 'match_'.'MinusProduct'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_299 = TRUE; break;
				}
				$result = $res_296;
				$this->pos = $pos_296;
				$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_299 = TRUE; break;
				}
				$result = $res_296;
				$this->pos = $pos_296;
				$_299 = FALSE; break;
			}
			while(0);
			if( $_299 === FALSE) { $_301 = FALSE; break; }
			$_301 = TRUE; break;
		}
		while(0);
		if( $_301 === FALSE) { $_311 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_310 = $result;
			$pos_310 = $this->pos;
			$_309 = NULL;
			do {
				$_307 = NULL;
				do {
					$res_304 = $result;
					$pos_304 = $this->pos;
					$matcher = 'match_'.'Plus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_307 = TRUE; break;
					}
					$result = $res_304;
					$this->pos = $pos_304;
					$matcher = 'match_'.'Minus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_307 = TRUE; break;
					}
					$result = $res_304;
					$this->pos = $pos_304;
					$_307 = FALSE; break;
				}
				while(0);
				if( $_307 === FALSE) { $_309 = FALSE; break; }
				$_309 = TRUE; break;
			}
			while(0);
			if( $_309 === FALSE) {
				$result = $res_310;
				$this->pos = $pos_310;
				unset( $res_310 );
				unset( $pos_310 );
				break;
			}
		}
		$_311 = TRUE; break;
	}
	while(0);
	if( $_311 === TRUE ) { return $this->finalise($result); }
	if( $_311 === FALSE) { return FALSE; }
}

public function Sum_MinusProduct ( &$result, $sub ) {
		$result['sum'] = $sub['expression'];
	}

public function Sum_Product ( &$result, $sub ) {
		$result['sum'] = $sub['expression'];
	}

public function Sum_Plus ( &$result, $sub ) {
		$result['sum'] = new ArithmeticBinaryExpression(
			ArithmeticBinaryExpression::TYPE_PLUS,
			$result['sum'],
			$sub['second_operand']['expression']
		);
	}

public function Sum_Minus ( &$result, $sub ) {
		$result['sum'] = new ArithmeticBinaryExpression(
			ArithmeticBinaryExpression::TYPE_MINUS,
			$result['sum'],
			$sub['second_operand']['expression']
		);
	}

/* ArithmeticExpression: Sum > */
protected $match_ArithmeticExpression_typestack = array('ArithmeticExpression');
function match_ArithmeticExpression ($stack = array()) {
	$matchrule = "ArithmeticExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_315 = NULL;
	do {
		$matcher = 'match_'.'Sum'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_315 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_315 = TRUE; break;
	}
	while(0);
	if( $_315 === TRUE ) { return $this->finalise($result); }
	if( $_315 === FALSE) { return FALSE; }
}

public function ArithmeticExpression_STR ( &$result, $sub ) {
		$result['expression'] = $sub['sum'];
	}

/* Greater: '>' > second_operand:ArithmeticExpression > */
protected $match_Greater_typestack = array('Greater');
function match_Greater ($stack = array()) {
	$matchrule = "Greater"; $result = $this->construct($matchrule, $matchrule, null);
	$_321 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
		}
		else { $_321 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ArithmeticExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_321 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_321 = TRUE; break;
	}
	while(0);
	if( $_321 === TRUE ) { return $this->finalise($result); }
	if( $_321 === FALSE) { return FALSE; }
}


/* Less: '<' > second_operand:ArithmeticExpression > */
protected $match_Less_typestack = array('Less');
function match_Less ($stack = array()) {
	$matchrule = "Less"; $result = $this->construct($matchrule, $matchrule, null);
	$_327 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '<') {
			$this->pos += 1;
			$result["text"] .= '<';
		}
		else { $_327 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ArithmeticExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_327 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_327 = TRUE; break;
	}
	while(0);
	if( $_327 === TRUE ) { return $this->finalise($result); }
	if( $_327 === FALSE) { return FALSE; }
}


/* LessOrEqual: '<=' > second_operand:ArithmeticExpression > */
protected $match_LessOrEqual_typestack = array('LessOrEqual');
function match_LessOrEqual ($stack = array()) {
	$matchrule = "LessOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_333 = NULL;
	do {
		if (( $subres = $this->literal( '<=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_333 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ArithmeticExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_333 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_333 = TRUE; break;
	}
	while(0);
	if( $_333 === TRUE ) { return $this->finalise($result); }
	if( $_333 === FALSE) { return FALSE; }
}


/* GreaterOrEqual: '>=' > second_operand:ArithmeticExpression > */
protected $match_GreaterOrEqual_typestack = array('GreaterOrEqual');
function match_GreaterOrEqual ($stack = array()) {
	$matchrule = "GreaterOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_339 = NULL;
	do {
		if (( $subres = $this->literal( '>=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_339 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ArithmeticExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_339 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_339 = TRUE; break;
	}
	while(0);
	if( $_339 === TRUE ) { return $this->finalise($result); }
	if( $_339 === FALSE) { return FALSE; }
}


/* Equal: '==' > second_operand:ArithmeticExpression > */
protected $match_Equal_typestack = array('Equal');
function match_Equal ($stack = array()) {
	$matchrule = "Equal"; $result = $this->construct($matchrule, $matchrule, null);
	$_345 = NULL;
	do {
		if (( $subres = $this->literal( '==' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_345 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ArithmeticExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_345 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_345 = TRUE; break;
	}
	while(0);
	if( $_345 === TRUE ) { return $this->finalise($result); }
	if( $_345 === FALSE) { return FALSE; }
}


/* NotEqual: '!=' > second_operand:ArithmeticExpression > */
protected $match_NotEqual_typestack = array('NotEqual');
function match_NotEqual ($stack = array()) {
	$matchrule = "NotEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_351 = NULL;
	do {
		if (( $subres = $this->literal( '!=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_351 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ArithmeticExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_351 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_351 = TRUE; break;
	}
	while(0);
	if( $_351 === TRUE ) { return $this->finalise($result); }
	if( $_351 === FALSE) { return FALSE; }
}


/* In: 'in' > second_operand:ArithmeticExpression > */
protected $match_In_typestack = array('In');
function match_In ($stack = array()) {
	$matchrule = "In"; $result = $this->construct($matchrule, $matchrule, null);
	$_357 = NULL;
	do {
		if (( $subres = $this->literal( 'in' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_357 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ArithmeticExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_357 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_357 = TRUE; break;
	}
	while(0);
	if( $_357 === TRUE ) { return $this->finalise($result); }
	if( $_357 === FALSE) { return FALSE; }
}


/* CompareExpression: ArithmeticExpression > ( Greater | Less | LessOrEqual | GreaterOrEqual | Equal | NotEqual | In )? */
protected $match_CompareExpression_typestack = array('CompareExpression');
function match_CompareExpression ($stack = array()) {
	$matchrule = "CompareExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_388 = NULL;
	do {
		$matcher = 'match_'.'ArithmeticExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_388 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_387 = $result;
		$pos_387 = $this->pos;
		$_386 = NULL;
		do {
			$_384 = NULL;
			do {
				$res_361 = $result;
				$pos_361 = $this->pos;
				$matcher = 'match_'.'Greater'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_384 = TRUE; break;
				}
				$result = $res_361;
				$this->pos = $pos_361;
				$_382 = NULL;
				do {
					$res_363 = $result;
					$pos_363 = $this->pos;
					$matcher = 'match_'.'Less'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_382 = TRUE; break;
					}
					$result = $res_363;
					$this->pos = $pos_363;
					$_380 = NULL;
					do {
						$res_365 = $result;
						$pos_365 = $this->pos;
						$matcher = 'match_'.'LessOrEqual'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_380 = TRUE; break;
						}
						$result = $res_365;
						$this->pos = $pos_365;
						$_378 = NULL;
						do {
							$res_367 = $result;
							$pos_367 = $this->pos;
							$matcher = 'match_'.'GreaterOrEqual'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_378 = TRUE; break;
							}
							$result = $res_367;
							$this->pos = $pos_367;
							$_376 = NULL;
							do {
								$res_369 = $result;
								$pos_369 = $this->pos;
								$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_376 = TRUE; break;
								}
								$result = $res_369;
								$this->pos = $pos_369;
								$_374 = NULL;
								do {
									$res_371 = $result;
									$pos_371 = $this->pos;
									$matcher = 'match_'.'NotEqual'; $key = $matcher; $pos = $this->pos;
									$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
									if ($subres !== FALSE) {
										$this->store( $result, $subres );
										$_374 = TRUE; break;
									}
									$result = $res_371;
									$this->pos = $pos_371;
									$matcher = 'match_'.'In'; $key = $matcher; $pos = $this->pos;
									$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
									if ($subres !== FALSE) {
										$this->store( $result, $subres );
										$_374 = TRUE; break;
									}
									$result = $res_371;
									$this->pos = $pos_371;
									$_374 = FALSE; break;
								}
								while(0);
								if( $_374 === TRUE ) { $_376 = TRUE; break; }
								$result = $res_369;
								$this->pos = $pos_369;
								$_376 = FALSE; break;
							}
							while(0);
							if( $_376 === TRUE ) { $_378 = TRUE; break; }
							$result = $res_367;
							$this->pos = $pos_367;
							$_378 = FALSE; break;
						}
						while(0);
						if( $_378 === TRUE ) { $_380 = TRUE; break; }
						$result = $res_365;
						$this->pos = $pos_365;
						$_380 = FALSE; break;
					}
					while(0);
					if( $_380 === TRUE ) { $_382 = TRUE; break; }
					$result = $res_363;
					$this->pos = $pos_363;
					$_382 = FALSE; break;
				}
				while(0);
				if( $_382 === TRUE ) { $_384 = TRUE; break; }
				$result = $res_361;
				$this->pos = $pos_361;
				$_384 = FALSE; break;
			}
			while(0);
			if( $_384 === FALSE) { $_386 = FALSE; break; }
			$_386 = TRUE; break;
		}
		while(0);
		if( $_386 === FALSE) {
			$result = $res_387;
			$this->pos = $pos_387;
			unset( $res_387 );
			unset( $pos_387 );
		}
		$_388 = TRUE; break;
	}
	while(0);
	if( $_388 === TRUE ) { return $this->finalise($result); }
	if( $_388 === FALSE) { return FALSE; }
}

public function CompareExpression_ArithmeticExpression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

public function CompareExpression_Greater ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_GREATER,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function CompareExpression_Less ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_LESS,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function CompareExpression_LessOrEqual ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_LESS_OR_EQUAL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function CompareExpression_GreaterOrEqual ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_GREATER_OR_EQUAL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function CompareExpression_Equal ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_EQUAL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function CompareExpression_NotEqual ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_NOT_EQUAL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function CompareExpression_In ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_IN,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

/* And: '&&' > second_operand:CompareExpression > */
protected $match_And_typestack = array('And');
function match_And ($stack = array()) {
	$matchrule = "And"; $result = $this->construct($matchrule, $matchrule, null);
	$_394 = NULL;
	do {
		if (( $subres = $this->literal( '&&' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_394 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'CompareExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_394 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_394 = TRUE; break;
	}
	while(0);
	if( $_394 === TRUE ) { return $this->finalise($result); }
	if( $_394 === FALSE) { return FALSE; }
}


/* Or: '||' > second_operand:CompareExpression > */
protected $match_Or_typestack = array('Or');
function match_Or ($stack = array()) {
	$matchrule = "Or"; $result = $this->construct($matchrule, $matchrule, null);
	$_400 = NULL;
	do {
		if (( $subres = $this->literal( '||' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_400 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'CompareExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_400 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_400 = TRUE; break;
	}
	while(0);
	if( $_400 === TRUE ) { return $this->finalise($result); }
	if( $_400 === FALSE) { return FALSE; }
}


/* BooleanExpression: CompareExpression > ( And | Or )* > */
protected $match_BooleanExpression_typestack = array('BooleanExpression');
function match_BooleanExpression ($stack = array()) {
	$matchrule = "BooleanExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_412 = NULL;
	do {
		$matcher = 'match_'.'CompareExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_412 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_410 = $result;
			$pos_410 = $this->pos;
			$_409 = NULL;
			do {
				$_407 = NULL;
				do {
					$res_404 = $result;
					$pos_404 = $this->pos;
					$matcher = 'match_'.'And'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_407 = TRUE; break;
					}
					$result = $res_404;
					$this->pos = $pos_404;
					$matcher = 'match_'.'Or'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_407 = TRUE; break;
					}
					$result = $res_404;
					$this->pos = $pos_404;
					$_407 = FALSE; break;
				}
				while(0);
				if( $_407 === FALSE) { $_409 = FALSE; break; }
				$_409 = TRUE; break;
			}
			while(0);
			if( $_409 === FALSE) {
				$result = $res_410;
				$this->pos = $pos_410;
				unset( $res_410 );
				unset( $pos_410 );
				break;
			}
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_412 = TRUE; break;
	}
	while(0);
	if( $_412 === TRUE ) { return $this->finalise($result); }
	if( $_412 === FALSE) { return FALSE; }
}

public function BooleanExpression_CompareExpression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

public function BooleanExpression_And ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_AND,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function BooleanExpression_Or ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_OR,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

/* Expression: BooleanExpression */
protected $match_Expression_typestack = array('Expression');
function match_Expression ($stack = array()) {
	$matchrule = "Expression"; $result = $this->construct($matchrule, $matchrule, null);
	$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
	$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
	if ($subres !== FALSE) {
		$this->store( $result, $subres );
		return $this->finalise($result);
	}
	else { return FALSE; }
}

public function Expression_STR ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}



}