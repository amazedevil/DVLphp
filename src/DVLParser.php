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

/* Number: /[0-9]+/ */
protected $match_Number_typestack = array('Number');
function match_Number ($stack = array()) {
	$matchrule = "Number"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->rx( '/[0-9]+/' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* String: "\'" val:(/([^'\\]|\\')* /) "\'" | '"' val:(/([^"\\]|\\")* /) '"'  */
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
				if (( $subres = $this->rx( '/([^\'\\\\]|\\\\\')* /' ) ) !== FALSE) { $result["text"] .= $subres; }
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
				if (( $subres = $this->rx( '/([^"\\\\]|\\\\")* /' ) ) !== FALSE) { $result["text"] .= $subres; }
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


/* Tag: !Boolean !This /[a-zA-Z0-9_]+/ */
protected $match_Tag_typestack = array('Tag');
function match_Tag ($stack = array()) {
	$matchrule = "Tag"; $result = $this->construct($matchrule, $matchrule, null);
	$_26 = NULL;
	do {
		$res_23 = $result;
		$pos_23 = $this->pos;
		$matcher = 'match_'.'Boolean'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$result = $res_23;
			$this->pos = $pos_23;
			$_26 = FALSE; break;
		}
		else {
			$result = $res_23;
			$this->pos = $pos_23;
		}
		$res_24 = $result;
		$pos_24 = $this->pos;
		$matcher = 'match_'.'This'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$result = $res_24;
			$this->pos = $pos_24;
			$_26 = FALSE; break;
		}
		else {
			$result = $res_24;
			$this->pos = $pos_24;
		}
		if (( $subres = $this->rx( '/[a-zA-Z0-9_]+/' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_26 = FALSE; break; }
		$_26 = TRUE; break;
	}
	while(0);
	if( $_26 === TRUE ) { return $this->finalise($result); }
	if( $_26 === FALSE) { return FALSE; }
}


/* Boolean: 'true' | 'false' */
protected $match_Boolean_typestack = array('Boolean');
function match_Boolean ($stack = array()) {
	$matchrule = "Boolean"; $result = $this->construct($matchrule, $matchrule, null);
	$_31 = NULL;
	do {
		$res_28 = $result;
		$pos_28 = $this->pos;
		if (( $subres = $this->literal( 'true' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$_31 = TRUE; break;
		}
		$result = $res_28;
		$this->pos = $pos_28;
		if (( $subres = $this->literal( 'false' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$_31 = TRUE; break;
		}
		$result = $res_28;
		$this->pos = $pos_28;
		$_31 = FALSE; break;
	}
	while(0);
	if( $_31 === TRUE ) { return $this->finalise($result); }
	if( $_31 === FALSE) { return FALSE; }
}


/* This: 'this' !/[a-zA-Z0-9_]/ */
protected $match_This_typestack = array('This');
function match_This ($stack = array()) {
	$matchrule = "This"; $result = $this->construct($matchrule, $matchrule, null);
	$_35 = NULL;
	do {
		if (( $subres = $this->literal( 'this' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_35 = FALSE; break; }
		$res_34 = $result;
		$pos_34 = $this->pos;
		if (( $subres = $this->rx( '/[a-zA-Z0-9_]/' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$result = $res_34;
			$this->pos = $pos_34;
			$_35 = FALSE; break;
		}
		else {
			$result = $res_34;
			$this->pos = $pos_34;
		}
		$_35 = TRUE; break;
	}
	while(0);
	if( $_35 === TRUE ) { return $this->finalise($result); }
	if( $_35 === FALSE) { return FALSE; }
}


/* Property: '.' Name > */
protected $match_Property_typestack = array('Property');
function match_Property ($stack = array()) {
	$matchrule = "Property"; $result = $this->construct($matchrule, $matchrule, null);
	$_40 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '.') {
			$this->pos += 1;
			$result["text"] .= '.';
		}
		else { $_40 = FALSE; break; }
		$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_40 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_40 = TRUE; break;
	}
	while(0);
	if( $_40 === TRUE ) { return $this->finalise($result); }
	if( $_40 === FALSE) { return FALSE; }
}

public function Property_Name ( &$result, $sub ) {
		$result['accessor'] = new PropertyAccessor($sub['text']);
	}

/* ArrayElement: '[' > ( selector:Expression )? > ']' > */
protected $match_ArrayElement_typestack = array('ArrayElement');
function match_ArrayElement ($stack = array()) {
	$matchrule = "ArrayElement"; $result = $this->construct($matchrule, $matchrule, null);
	$_50 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '[') {
			$this->pos += 1;
			$result["text"] .= '[';
		}
		else { $_50 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_46 = $result;
		$pos_46 = $this->pos;
		$_45 = NULL;
		do {
			$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres, "selector" );
			}
			else { $_45 = FALSE; break; }
			$_45 = TRUE; break;
		}
		while(0);
		if( $_45 === FALSE) {
			$result = $res_46;
			$this->pos = $pos_46;
			unset( $res_46 );
			unset( $pos_46 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ']') {
			$this->pos += 1;
			$result["text"] .= ']';
		}
		else { $_50 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_50 = TRUE; break;
	}
	while(0);
	if( $_50 === TRUE ) { return $this->finalise($result); }
	if( $_50 === FALSE) { return FALSE; }
}


/* Variable: ( Name | This ) (Property | ArrayElement)* > */
protected $match_Variable_typestack = array('Variable');
function match_Variable ($stack = array()) {
	$matchrule = "Variable"; $result = $this->construct($matchrule, $matchrule, null);
	$_67 = NULL;
	do {
		$_57 = NULL;
		do {
			$_55 = NULL;
			do {
				$res_52 = $result;
				$pos_52 = $this->pos;
				$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_55 = TRUE; break;
				}
				$result = $res_52;
				$this->pos = $pos_52;
				$matcher = 'match_'.'This'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_55 = TRUE; break;
				}
				$result = $res_52;
				$this->pos = $pos_52;
				$_55 = FALSE; break;
			}
			while(0);
			if( $_55 === FALSE) { $_57 = FALSE; break; }
			$_57 = TRUE; break;
		}
		while(0);
		if( $_57 === FALSE) { $_67 = FALSE; break; }
		while (true) {
			$res_65 = $result;
			$pos_65 = $this->pos;
			$_64 = NULL;
			do {
				$_62 = NULL;
				do {
					$res_59 = $result;
					$pos_59 = $this->pos;
					$matcher = 'match_'.'Property'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_62 = TRUE; break;
					}
					$result = $res_59;
					$this->pos = $pos_59;
					$matcher = 'match_'.'ArrayElement'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_62 = TRUE; break;
					}
					$result = $res_59;
					$this->pos = $pos_59;
					$_62 = FALSE; break;
				}
				while(0);
				if( $_62 === FALSE) { $_64 = FALSE; break; }
				$_64 = TRUE; break;
			}
			while(0);
			if( $_64 === FALSE) {
				$result = $res_65;
				$this->pos = $pos_65;
				unset( $res_65 );
				unset( $pos_65 );
				break;
			}
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_67 = TRUE; break;
	}
	while(0);
	if( $_67 === TRUE ) { return $this->finalise($result); }
	if( $_67 === FALSE) { return FALSE; }
}

public function Variable_Name ( &$result, $sub ) {
		$result['variable'] = new VariableExpression([ new VariableAccessor($sub['text']) ]);
	}

public function Variable_This ( &$result, $sub ) {
		$result['variable'] = new VariableExpression([ new ThisAccessor() ]);
	}

public function Variable_Property ( &$result, $sub ) {
		$result['variable']->addAccessor($sub['accessor']);
	}

public function Variable_ArrayElement ( &$result, $sub ) {
		$result['variable']->addAccessor(new CollectionAccessor(
			isset($sub['selector']['expression']) ? $sub['selector']['expression'] : null
		));
	}

/* Function: Name '(' > ( Expression )? > ( ',' > Expression > )* ')' > */
protected $match_Function_typestack = array('Function');
function match_Function ($stack = array()) {
	$matchrule = "Function"; $result = $this->construct($matchrule, $matchrule, null);
	$_84 = NULL;
	do {
		$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_84 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_84 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_74 = $result;
		$pos_74 = $this->pos;
		$_73 = NULL;
		do {
			$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_73 = FALSE; break; }
			$_73 = TRUE; break;
		}
		while(0);
		if( $_73 === FALSE) {
			$result = $res_74;
			$this->pos = $pos_74;
			unset( $res_74 );
			unset( $pos_74 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_81 = $result;
			$pos_81 = $this->pos;
			$_80 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_80 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_80 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_80 = TRUE; break;
			}
			while(0);
			if( $_80 === FALSE) {
				$result = $res_81;
				$this->pos = $pos_81;
				unset( $res_81 );
				unset( $pos_81 );
				break;
			}
		}
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_84 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_84 = TRUE; break;
	}
	while(0);
	if( $_84 === TRUE ) { return $this->finalise($result); }
	if( $_84 === FALSE) { return FALSE; }
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
	$_94 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_94 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_94 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_94 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_94 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_94 = TRUE; break;
	}
	while(0);
	if( $_94 === TRUE ) { return $this->finalise($result); }
	if( $_94 === FALSE) { return FALSE; }
}

public function Use_Expression ( &$result, $sub ) {
		$result['validation'] = new UseValidation( $sub['expression'] );
	}

public function Use_ValidationControl ( &$result, $sub ) {
		$result['validation']->setValidation( $sub['validation'] );
	}

/* Ternary: '(' > BooleanExpression > ')' > '?' > ValidationControl > ( ':' > ValidationControl > )? */
protected $match_Ternary_typestack = array('Ternary');
function match_Ternary ($stack = array()) {
	$matchrule = "Ternary"; $result = $this->construct($matchrule, $matchrule, null);
	$_112 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_112 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_112 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_112 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == '?') {
			$this->pos += 1;
			$result["text"] .= '?';
		}
		else { $_112 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_112 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_111 = $result;
		$pos_111 = $this->pos;
		$_110 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == ':') {
				$this->pos += 1;
				$result["text"] .= ':';
			}
			else { $_110 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_110 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_110 = TRUE; break;
		}
		while(0);
		if( $_110 === FALSE) {
			$result = $res_111;
			$this->pos = $pos_111;
			unset( $res_111 );
			unset( $pos_111 );
		}
		$_112 = TRUE; break;
	}
	while(0);
	if( $_112 === TRUE ) { return $this->finalise($result); }
	if( $_112 === FALSE) { return FALSE; }
}

public function Ternary_BooleanExpression ( &$result, $sub ) {
		$result['validation'] = new TernaryValidation( $sub['expression'] );
	}

public function Ternary_ValidationControl ( &$result, $sub ) {
		if (!$result['validation']->hasPositive()) {
			$result['validation']->setPositive( $sub['validation'] );
		} else {
			$result['validation']->setNegative( $sub['validation'] );
		}
	}

/* Foreach: '$(' > ArrayExpression > ( ':' > Name > ( '=>' > Name )? )? > ')' > ValidationControl > */
protected $match_Foreach_typestack = array('Foreach');
function match_Foreach ($stack = array()) {
	$matchrule = "Foreach"; $result = $this->construct($matchrule, $matchrule, null);
	$_134 = NULL;
	do {
		if (( $subres = $this->literal( '$(' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_134 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ArrayExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_134 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_128 = $result;
		$pos_128 = $this->pos;
		$_127 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == ':') {
				$this->pos += 1;
				$result["text"] .= ':';
			}
			else { $_127 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_127 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$res_126 = $result;
			$pos_126 = $this->pos;
			$_125 = NULL;
			do {
				if (( $subres = $this->literal( '=>' ) ) !== FALSE) { $result["text"] .= $subres; }
				else { $_125 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_125 = FALSE; break; }
				$_125 = TRUE; break;
			}
			while(0);
			if( $_125 === FALSE) {
				$result = $res_126;
				$this->pos = $pos_126;
				unset( $res_126 );
				unset( $pos_126 );
			}
			$_127 = TRUE; break;
		}
		while(0);
		if( $_127 === FALSE) {
			$result = $res_128;
			$this->pos = $pos_128;
			unset( $res_128 );
			unset( $pos_128 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_134 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_134 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_134 = TRUE; break;
	}
	while(0);
	if( $_134 === TRUE ) { return $this->finalise($result); }
	if( $_134 === FALSE) { return FALSE; }
}

public function Foreach_ArrayExpression ( &$result, $sub ) {
		$result['validation'] = new ForeachValidation( $sub['expression'] );
	}

public function Foreach_Name ( &$result, $sub ) {
		if ($result['validation']->valueName === null) {
			$result['validation']->valueName = $sub['text'];
		} else {
			$result['validation']->keyName = $result['validation']->valueName;
			$result['validation']->valueName = $sub['text'];
		}
	}

public function Foreach_ValidationControl ( &$result, $sub ) {
		$result['validation']->setValidation( $sub['validation'] );
	}

/* Group: '{' > ValidationControl > ( ',' > ValidationControl > )* '}' > */
protected $match_Group_typestack = array('Group');
function match_Group ($stack = array()) {
	$matchrule = "Group"; $result = $this->construct($matchrule, $matchrule, null);
	$_148 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '{') {
			$this->pos += 1;
			$result["text"] .= '{';
		}
		else { $_148 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_148 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_145 = $result;
			$pos_145 = $this->pos;
			$_144 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_144 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_144 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_144 = TRUE; break;
			}
			while(0);
			if( $_144 === FALSE) {
				$result = $res_145;
				$this->pos = $pos_145;
				unset( $res_145 );
				unset( $pos_145 );
				break;
			}
		}
		if (substr($this->string,$this->pos,1) == '}') {
			$this->pos += 1;
			$result["text"] .= '}';
		}
		else { $_148 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_148 = TRUE; break;
	}
	while(0);
	if( $_148 === TRUE ) { return $this->finalise($result); }
	if( $_148 === FALSE) { return FALSE; }
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
	$_165 = NULL;
	do {
		$res_150 = $result;
		$pos_150 = $this->pos;
		$matcher = 'match_'.'Group'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_165 = TRUE; break;
		}
		$result = $res_150;
		$this->pos = $pos_150;
		$_163 = NULL;
		do {
			$res_152 = $result;
			$pos_152 = $this->pos;
			$matcher = 'match_'.'Foreach'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_163 = TRUE; break;
			}
			$result = $res_152;
			$this->pos = $pos_152;
			$_161 = NULL;
			do {
				$res_154 = $result;
				$pos_154 = $this->pos;
				$matcher = 'match_'.'Ternary'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_161 = TRUE; break;
				}
				$result = $res_154;
				$this->pos = $pos_154;
				$_159 = NULL;
				do {
					$res_156 = $result;
					$pos_156 = $this->pos;
					$matcher = 'match_'.'Use'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_159 = TRUE; break;
					}
					$result = $res_156;
					$this->pos = $pos_156;
					$matcher = 'match_'.'Validation'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_159 = TRUE; break;
					}
					$result = $res_156;
					$this->pos = $pos_156;
					$_159 = FALSE; break;
				}
				while(0);
				if( $_159 === TRUE ) { $_161 = TRUE; break; }
				$result = $res_154;
				$this->pos = $pos_154;
				$_161 = FALSE; break;
			}
			while(0);
			if( $_161 === TRUE ) { $_163 = TRUE; break; }
			$result = $res_152;
			$this->pos = $pos_152;
			$_163 = FALSE; break;
		}
		while(0);
		if( $_163 === TRUE ) { $_165 = TRUE; break; }
		$result = $res_150;
		$this->pos = $pos_150;
		$_165 = FALSE; break;
	}
	while(0);
	if( $_165 === TRUE ) { return $this->finalise($result); }
	if( $_165 === FALSE) { return FALSE; }
}

public function ValidationControl_STR ( &$result, $sub ) {
		$result['validation'] = $sub['validation'];
	}

/* Validation: Expression ( > '@' > String > ( ':' > Tag )? )? > */
protected $match_Validation_typestack = array('Validation');
function match_Validation ($stack = array()) {
	$matchrule = "Validation"; $result = $this->construct($matchrule, $matchrule, null);
	$_181 = NULL;
	do {
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_181 = FALSE; break; }
		$res_179 = $result;
		$pos_179 = $this->pos;
		$_178 = NULL;
		do {
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			if (substr($this->string,$this->pos,1) == '@') {
				$this->pos += 1;
				$result["text"] .= '@';
			}
			else { $_178 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_178 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$res_177 = $result;
			$pos_177 = $this->pos;
			$_176 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ':') {
					$this->pos += 1;
					$result["text"] .= ':';
				}
				else { $_176 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Tag'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_176 = FALSE; break; }
				$_176 = TRUE; break;
			}
			while(0);
			if( $_176 === FALSE) {
				$result = $res_177;
				$this->pos = $pos_177;
				unset( $res_177 );
				unset( $pos_177 );
			}
			$_178 = TRUE; break;
		}
		while(0);
		if( $_178 === FALSE) {
			$result = $res_179;
			$this->pos = $pos_179;
			unset( $res_179 );
			unset( $pos_179 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_181 = TRUE; break;
	}
	while(0);
	if( $_181 === TRUE ) { return $this->finalise($result); }
	if( $_181 === FALSE) { return FALSE; }
}

public function Validation_Expression ( &$result, $sub ) {
		$result['validation'] = new Validation( $sub['expression'] );
	}

public function Validation_String ( &$result, $sub ) {
		$result['validation']->setMessage($sub['val']['text']);
	}

public function Validation_Tag ( &$result, $sub ) {
		echo "Validation - Tag\n";
		$result['validation']->setTag($sub['text']);
	}

/* BooleanValue: Boolean > | Function > | Variable > | '(' > BooleanExpression > ')' > */
protected $match_BooleanValue_typestack = array('BooleanValue');
function match_BooleanValue ($stack = array()) {
	$matchrule = "BooleanValue"; $result = $this->construct($matchrule, $matchrule, null);
	$_210 = NULL;
	do {
		$res_183 = $result;
		$pos_183 = $this->pos;
		$_186 = NULL;
		do {
			$matcher = 'match_'.'Boolean'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_186 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_186 = TRUE; break;
		}
		while(0);
		if( $_186 === TRUE ) { $_210 = TRUE; break; }
		$result = $res_183;
		$this->pos = $pos_183;
		$_208 = NULL;
		do {
			$res_188 = $result;
			$pos_188 = $this->pos;
			$_191 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_191 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_191 = TRUE; break;
			}
			while(0);
			if( $_191 === TRUE ) { $_208 = TRUE; break; }
			$result = $res_188;
			$this->pos = $pos_188;
			$_206 = NULL;
			do {
				$res_193 = $result;
				$pos_193 = $this->pos;
				$_196 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_196 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_196 = TRUE; break;
				}
				while(0);
				if( $_196 === TRUE ) { $_206 = TRUE; break; }
				$result = $res_193;
				$this->pos = $pos_193;
				$_204 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_204 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_204 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_204 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_204 = TRUE; break;
				}
				while(0);
				if( $_204 === TRUE ) { $_206 = TRUE; break; }
				$result = $res_193;
				$this->pos = $pos_193;
				$_206 = FALSE; break;
			}
			while(0);
			if( $_206 === TRUE ) { $_208 = TRUE; break; }
			$result = $res_188;
			$this->pos = $pos_188;
			$_208 = FALSE; break;
		}
		while(0);
		if( $_208 === TRUE ) { $_210 = TRUE; break; }
		$result = $res_183;
		$this->pos = $pos_183;
		$_210 = FALSE; break;
	}
	while(0);
	if( $_210 === TRUE ) { return $this->finalise($result); }
	if( $_210 === FALSE) { return FALSE; }
}

public function BooleanValue_Boolean ( &$result, $sub ) {
		$result['expression'] = new BooleanConstExpression($sub['text']);
	}

public function BooleanValue_Function ( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}

public function BooleanValue_Variable ( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}

public function BooleanValue_BooleanExpression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* EqualityComparableExpression: NumericExpression | BooleanValue | StringExpression > */
protected $match_EqualityComparableExpression_typestack = array('EqualityComparableExpression');
function match_EqualityComparableExpression ($stack = array()) {
	$matchrule = "EqualityComparableExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_222 = NULL;
	do {
		$res_212 = $result;
		$pos_212 = $this->pos;
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_222 = TRUE; break;
		}
		$result = $res_212;
		$this->pos = $pos_212;
		$_220 = NULL;
		do {
			$res_214 = $result;
			$pos_214 = $this->pos;
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_220 = TRUE; break;
			}
			$result = $res_214;
			$this->pos = $pos_214;
			$_218 = NULL;
			do {
				$matcher = 'match_'.'StringExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_218 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_218 = TRUE; break;
			}
			while(0);
			if( $_218 === TRUE ) { $_220 = TRUE; break; }
			$result = $res_214;
			$this->pos = $pos_214;
			$_220 = FALSE; break;
		}
		while(0);
		if( $_220 === TRUE ) { $_222 = TRUE; break; }
		$result = $res_212;
		$this->pos = $pos_212;
		$_222 = FALSE; break;
	}
	while(0);
	if( $_222 === TRUE ) { return $this->finalise($result); }
	if( $_222 === FALSE) { return FALSE; }
}

public function EqualityComparableExpression_STR ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* Greater: '>' > second_operand:NumericExpression > */
protected $match_Greater_typestack = array('Greater');
function match_Greater ($stack = array()) {
	$matchrule = "Greater"; $result = $this->construct($matchrule, $matchrule, null);
	$_228 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
		}
		else { $_228 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_228 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_228 = TRUE; break;
	}
	while(0);
	if( $_228 === TRUE ) { return $this->finalise($result); }
	if( $_228 === FALSE) { return FALSE; }
}


/* Less: '<' > second_operand:NumericExpression > */
protected $match_Less_typestack = array('Less');
function match_Less ($stack = array()) {
	$matchrule = "Less"; $result = $this->construct($matchrule, $matchrule, null);
	$_234 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '<') {
			$this->pos += 1;
			$result["text"] .= '<';
		}
		else { $_234 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_234 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_234 = TRUE; break;
	}
	while(0);
	if( $_234 === TRUE ) { return $this->finalise($result); }
	if( $_234 === FALSE) { return FALSE; }
}


/* LessOrEqual: '<=' > second_operand:NumericExpression > */
protected $match_LessOrEqual_typestack = array('LessOrEqual');
function match_LessOrEqual ($stack = array()) {
	$matchrule = "LessOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_240 = NULL;
	do {
		if (( $subres = $this->literal( '<=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_240 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_240 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_240 = TRUE; break;
	}
	while(0);
	if( $_240 === TRUE ) { return $this->finalise($result); }
	if( $_240 === FALSE) { return FALSE; }
}


/* GreaterOrEqual: '>=' > second_operand:NumericExpression > */
protected $match_GreaterOrEqual_typestack = array('GreaterOrEqual');
function match_GreaterOrEqual ($stack = array()) {
	$matchrule = "GreaterOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_246 = NULL;
	do {
		if (( $subres = $this->literal( '>=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_246 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_246 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_246 = TRUE; break;
	}
	while(0);
	if( $_246 === TRUE ) { return $this->finalise($result); }
	if( $_246 === FALSE) { return FALSE; }
}


/* Equal: '==' > second_operand:EqualityComparableExpression > */
protected $match_Equal_typestack = array('Equal');
function match_Equal ($stack = array()) {
	$matchrule = "Equal"; $result = $this->construct($matchrule, $matchrule, null);
	$_252 = NULL;
	do {
		if (( $subres = $this->literal( '==' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_252 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_252 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_252 = TRUE; break;
	}
	while(0);
	if( $_252 === TRUE ) { return $this->finalise($result); }
	if( $_252 === FALSE) { return FALSE; }
}


/* NotEqual: '!=' > second_operand:EqualityComparableExpression > */
protected $match_NotEqual_typestack = array('NotEqual');
function match_NotEqual ($stack = array()) {
	$matchrule = "NotEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_258 = NULL;
	do {
		if (( $subres = $this->literal( '!=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_258 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_258 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_258 = TRUE; break;
	}
	while(0);
	if( $_258 === TRUE ) { return $this->finalise($result); }
	if( $_258 === FALSE) { return FALSE; }
}


/* And: '&&' > second_operand:BooleanValue > */
protected $match_And_typestack = array('And');
function match_And ($stack = array()) {
	$matchrule = "And"; $result = $this->construct($matchrule, $matchrule, null);
	$_264 = NULL;
	do {
		if (( $subres = $this->literal( '&&' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_264 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_264 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_264 = TRUE; break;
	}
	while(0);
	if( $_264 === TRUE ) { return $this->finalise($result); }
	if( $_264 === FALSE) { return FALSE; }
}


/* Or: '||' > second_operand:BooleanValue > */
protected $match_Or_typestack = array('Or');
function match_Or ($stack = array()) {
	$matchrule = "Or"; $result = $this->construct($matchrule, $matchrule, null);
	$_270 = NULL;
	do {
		if (( $subres = $this->literal( '||' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_270 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_270 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_270 = TRUE; break;
	}
	while(0);
	if( $_270 === TRUE ) { return $this->finalise($result); }
	if( $_270 === FALSE) { return FALSE; }
}


/* Not: '!' BooleanValue > */
protected $match_Not_typestack = array('Not');
function match_Not ($stack = array()) {
	$matchrule = "Not"; $result = $this->construct($matchrule, $matchrule, null);
	$_275 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '!') {
			$this->pos += 1;
			$result["text"] .= '!';
		}
		else { $_275 = FALSE; break; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_275 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_275 = TRUE; break;
	}
	while(0);
	if( $_275 === TRUE ) { return $this->finalise($result); }
	if( $_275 === FALSE) { return FALSE; }
}

public function Not_BooleanValue ( &$result, $sub ) {
		$result['expression'] = new BooleanUnaryExpression(BooleanUnaryExpression::TYPE_NOT, $sub['expression']);
	}

/* BooleanBinaryOperatorSign: '>' | '<' | '>=' | '<=' | '==' | '!=' | '&&' | '||' */
protected $match_BooleanBinaryOperatorSign_typestack = array('BooleanBinaryOperatorSign');
function match_BooleanBinaryOperatorSign ($stack = array()) {
	$matchrule = "BooleanBinaryOperatorSign"; $result = $this->construct($matchrule, $matchrule, null);
	$_304 = NULL;
	do {
		$res_277 = $result;
		$pos_277 = $this->pos;
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
			$_304 = TRUE; break;
		}
		$result = $res_277;
		$this->pos = $pos_277;
		$_302 = NULL;
		do {
			$res_279 = $result;
			$pos_279 = $this->pos;
			if (substr($this->string,$this->pos,1) == '<') {
				$this->pos += 1;
				$result["text"] .= '<';
				$_302 = TRUE; break;
			}
			$result = $res_279;
			$this->pos = $pos_279;
			$_300 = NULL;
			do {
				$res_281 = $result;
				$pos_281 = $this->pos;
				if (( $subres = $this->literal( '>=' ) ) !== FALSE) {
					$result["text"] .= $subres;
					$_300 = TRUE; break;
				}
				$result = $res_281;
				$this->pos = $pos_281;
				$_298 = NULL;
				do {
					$res_283 = $result;
					$pos_283 = $this->pos;
					if (( $subres = $this->literal( '<=' ) ) !== FALSE) {
						$result["text"] .= $subres;
						$_298 = TRUE; break;
					}
					$result = $res_283;
					$this->pos = $pos_283;
					$_296 = NULL;
					do {
						$res_285 = $result;
						$pos_285 = $this->pos;
						if (( $subres = $this->literal( '==' ) ) !== FALSE) {
							$result["text"] .= $subres;
							$_296 = TRUE; break;
						}
						$result = $res_285;
						$this->pos = $pos_285;
						$_294 = NULL;
						do {
							$res_287 = $result;
							$pos_287 = $this->pos;
							if (( $subres = $this->literal( '!=' ) ) !== FALSE) {
								$result["text"] .= $subres;
								$_294 = TRUE; break;
							}
							$result = $res_287;
							$this->pos = $pos_287;
							$_292 = NULL;
							do {
								$res_289 = $result;
								$pos_289 = $this->pos;
								if (( $subres = $this->literal( '&&' ) ) !== FALSE) {
									$result["text"] .= $subres;
									$_292 = TRUE; break;
								}
								$result = $res_289;
								$this->pos = $pos_289;
								if (( $subres = $this->literal( '||' ) ) !== FALSE) {
									$result["text"] .= $subres;
									$_292 = TRUE; break;
								}
								$result = $res_289;
								$this->pos = $pos_289;
								$_292 = FALSE; break;
							}
							while(0);
							if( $_292 === TRUE ) { $_294 = TRUE; break; }
							$result = $res_287;
							$this->pos = $pos_287;
							$_294 = FALSE; break;
						}
						while(0);
						if( $_294 === TRUE ) { $_296 = TRUE; break; }
						$result = $res_285;
						$this->pos = $pos_285;
						$_296 = FALSE; break;
					}
					while(0);
					if( $_296 === TRUE ) { $_298 = TRUE; break; }
					$result = $res_283;
					$this->pos = $pos_283;
					$_298 = FALSE; break;
				}
				while(0);
				if( $_298 === TRUE ) { $_300 = TRUE; break; }
				$result = $res_281;
				$this->pos = $pos_281;
				$_300 = FALSE; break;
			}
			while(0);
			if( $_300 === TRUE ) { $_302 = TRUE; break; }
			$result = $res_279;
			$this->pos = $pos_279;
			$_302 = FALSE; break;
		}
		while(0);
		if( $_302 === TRUE ) { $_304 = TRUE; break; }
		$result = $res_277;
		$this->pos = $pos_277;
		$_304 = FALSE; break;
	}
	while(0);
	if( $_304 === TRUE ) { return $this->finalise($result); }
	if( $_304 === FALSE) { return FALSE; }
}


/* BooleanOperation: Not | NumericExpression > ( Greater | Less | LessOrEqual | GreaterOrEqual ) | EqualityComparableExpression > ( Equal | NotEqual ) | BooleanValue > ( And | Or ) > */
protected $match_BooleanOperation_typestack = array('BooleanOperation');
function match_BooleanOperation ($stack = array()) {
	$matchrule = "BooleanOperation"; $result = $this->construct($matchrule, $matchrule, null);
	$_356 = NULL;
	do {
		$res_306 = $result;
		$pos_306 = $this->pos;
		$matcher = 'match_'.'Not'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_356 = TRUE; break;
		}
		$result = $res_306;
		$this->pos = $pos_306;
		$_354 = NULL;
		do {
			$res_308 = $result;
			$pos_308 = $this->pos;
			$_326 = NULL;
			do {
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_326 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_324 = NULL;
				do {
					$_322 = NULL;
					do {
						$res_311 = $result;
						$pos_311 = $this->pos;
						$matcher = 'match_'.'Greater'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_322 = TRUE; break;
						}
						$result = $res_311;
						$this->pos = $pos_311;
						$_320 = NULL;
						do {
							$res_313 = $result;
							$pos_313 = $this->pos;
							$matcher = 'match_'.'Less'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_320 = TRUE; break;
							}
							$result = $res_313;
							$this->pos = $pos_313;
							$_318 = NULL;
							do {
								$res_315 = $result;
								$pos_315 = $this->pos;
								$matcher = 'match_'.'LessOrEqual'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_318 = TRUE; break;
								}
								$result = $res_315;
								$this->pos = $pos_315;
								$matcher = 'match_'.'GreaterOrEqual'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_318 = TRUE; break;
								}
								$result = $res_315;
								$this->pos = $pos_315;
								$_318 = FALSE; break;
							}
							while(0);
							if( $_318 === TRUE ) { $_320 = TRUE; break; }
							$result = $res_313;
							$this->pos = $pos_313;
							$_320 = FALSE; break;
						}
						while(0);
						if( $_320 === TRUE ) { $_322 = TRUE; break; }
						$result = $res_311;
						$this->pos = $pos_311;
						$_322 = FALSE; break;
					}
					while(0);
					if( $_322 === FALSE) { $_324 = FALSE; break; }
					$_324 = TRUE; break;
				}
				while(0);
				if( $_324 === FALSE) { $_326 = FALSE; break; }
				$_326 = TRUE; break;
			}
			while(0);
			if( $_326 === TRUE ) { $_354 = TRUE; break; }
			$result = $res_308;
			$this->pos = $pos_308;
			$_352 = NULL;
			do {
				$res_328 = $result;
				$pos_328 = $this->pos;
				$_338 = NULL;
				do {
					$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_338 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_336 = NULL;
					do {
						$_334 = NULL;
						do {
							$res_331 = $result;
							$pos_331 = $this->pos;
							$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_334 = TRUE; break;
							}
							$result = $res_331;
							$this->pos = $pos_331;
							$matcher = 'match_'.'NotEqual'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_334 = TRUE; break;
							}
							$result = $res_331;
							$this->pos = $pos_331;
							$_334 = FALSE; break;
						}
						while(0);
						if( $_334 === FALSE) { $_336 = FALSE; break; }
						$_336 = TRUE; break;
					}
					while(0);
					if( $_336 === FALSE) { $_338 = FALSE; break; }
					$_338 = TRUE; break;
				}
				while(0);
				if( $_338 === TRUE ) { $_352 = TRUE; break; }
				$result = $res_328;
				$this->pos = $pos_328;
				$_350 = NULL;
				do {
					$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_350 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_347 = NULL;
					do {
						$_345 = NULL;
						do {
							$res_342 = $result;
							$pos_342 = $this->pos;
							$matcher = 'match_'.'And'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_345 = TRUE; break;
							}
							$result = $res_342;
							$this->pos = $pos_342;
							$matcher = 'match_'.'Or'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_345 = TRUE; break;
							}
							$result = $res_342;
							$this->pos = $pos_342;
							$_345 = FALSE; break;
						}
						while(0);
						if( $_345 === FALSE) { $_347 = FALSE; break; }
						$_347 = TRUE; break;
					}
					while(0);
					if( $_347 === FALSE) { $_350 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_350 = TRUE; break;
				}
				while(0);
				if( $_350 === TRUE ) { $_352 = TRUE; break; }
				$result = $res_328;
				$this->pos = $pos_328;
				$_352 = FALSE; break;
			}
			while(0);
			if( $_352 === TRUE ) { $_354 = TRUE; break; }
			$result = $res_308;
			$this->pos = $pos_308;
			$_354 = FALSE; break;
		}
		while(0);
		if( $_354 === TRUE ) { $_356 = TRUE; break; }
		$result = $res_306;
		$this->pos = $pos_306;
		$_356 = FALSE; break;
	}
	while(0);
	if( $_356 === TRUE ) { return $this->finalise($result); }
	if( $_356 === FALSE) { return FALSE; }
}

public function BooleanOperation_Not ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

public function BooleanOperation_NumericExpression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

public function BooleanOperation_Greater ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_GREATER,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function BooleanOperation_Less ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_LESS,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function BooleanOperation_LessOrEqual ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_LESS_OR_EQUAL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function BooleanOperation_GreaterOrEqual ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_GREATER_OR_EQUAL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function BooleanOperation_EqualityComparableExpression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

public function BooleanOperation_Equal ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_EQUAL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function BooleanOperation_NotEqual ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_NOT_EQUAL,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function BooleanOperation_BooleanValue ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

public function BooleanOperation_And ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_AND,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

public function BooleanOperation_Or ( &$result, $sub ) {
		$result['expression'] = new BooleanBinaryExpression(
			BooleanBinaryExpression::TYPE_OR,
			$result['expression'],
			$sub['second_operand']['expression']
		);
	}

/* BooleanExpression: BooleanOperation | BooleanValue > */
protected $match_BooleanExpression_typestack = array('BooleanExpression');
function match_BooleanExpression ($stack = array()) {
	$matchrule = "BooleanExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_364 = NULL;
	do {
		$res_358 = $result;
		$pos_358 = $this->pos;
		$matcher = 'match_'.'BooleanOperation'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_364 = TRUE; break;
		}
		$result = $res_358;
		$this->pos = $pos_358;
		$_362 = NULL;
		do {
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_362 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_362 = TRUE; break;
		}
		while(0);
		if( $_362 === TRUE ) { $_364 = TRUE; break; }
		$result = $res_358;
		$this->pos = $pos_358;
		$_364 = FALSE; break;
	}
	while(0);
	if( $_364 === TRUE ) { return $this->finalise($result); }
	if( $_364 === FALSE) { return FALSE; }
}

public function BooleanExpression_STR ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* NumericValue: Number > | Function > | Variable > | '(' > NumericExpression > ')' > */
protected $match_NumericValue_typestack = array('NumericValue');
function match_NumericValue ($stack = array()) {
	$matchrule = "NumericValue"; $result = $this->construct($matchrule, $matchrule, null);
	$_393 = NULL;
	do {
		$res_366 = $result;
		$pos_366 = $this->pos;
		$_369 = NULL;
		do {
			$matcher = 'match_'.'Number'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_369 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_369 = TRUE; break;
		}
		while(0);
		if( $_369 === TRUE ) { $_393 = TRUE; break; }
		$result = $res_366;
		$this->pos = $pos_366;
		$_391 = NULL;
		do {
			$res_371 = $result;
			$pos_371 = $this->pos;
			$_374 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_374 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_374 = TRUE; break;
			}
			while(0);
			if( $_374 === TRUE ) { $_391 = TRUE; break; }
			$result = $res_371;
			$this->pos = $pos_371;
			$_389 = NULL;
			do {
				$res_376 = $result;
				$pos_376 = $this->pos;
				$_379 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_379 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_379 = TRUE; break;
				}
				while(0);
				if( $_379 === TRUE ) { $_389 = TRUE; break; }
				$result = $res_376;
				$this->pos = $pos_376;
				$_387 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_387 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_387 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_387 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_387 = TRUE; break;
				}
				while(0);
				if( $_387 === TRUE ) { $_389 = TRUE; break; }
				$result = $res_376;
				$this->pos = $pos_376;
				$_389 = FALSE; break;
			}
			while(0);
			if( $_389 === TRUE ) { $_391 = TRUE; break; }
			$result = $res_371;
			$this->pos = $pos_371;
			$_391 = FALSE; break;
		}
		while(0);
		if( $_391 === TRUE ) { $_393 = TRUE; break; }
		$result = $res_366;
		$this->pos = $pos_366;
		$_393 = FALSE; break;
	}
	while(0);
	if( $_393 === TRUE ) { return $this->finalise($result); }
	if( $_393 === FALSE) { return FALSE; }
}

public function NumericValue_Number ( &$result, $sub ) {
		$result['expression'] = new ArithmeticConstExpression($sub['text']);
	}

public function NumericValue_Function ( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}

public function NumericValue_Variable ( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}

public function NumericValue_NumericExpression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* Mul: '*' > second_operand:NumericValue > */
protected $match_Mul_typestack = array('Mul');
function match_Mul ($stack = array()) {
	$matchrule = "Mul"; $result = $this->construct($matchrule, $matchrule, null);
	$_399 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '*') {
			$this->pos += 1;
			$result["text"] .= '*';
		}
		else { $_399 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_399 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_399 = TRUE; break;
	}
	while(0);
	if( $_399 === TRUE ) { return $this->finalise($result); }
	if( $_399 === FALSE) { return FALSE; }
}


/* Div: '/' > second_operand:NumericValue > */
protected $match_Div_typestack = array('Div');
function match_Div ($stack = array()) {
	$matchrule = "Div"; $result = $this->construct($matchrule, $matchrule, null);
	$_405 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '/') {
			$this->pos += 1;
			$result["text"] .= '/';
		}
		else { $_405 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_405 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_405 = TRUE; break;
	}
	while(0);
	if( $_405 === TRUE ) { return $this->finalise($result); }
	if( $_405 === FALSE) { return FALSE; }
}


/* Mod: '%' > second_operand:NumericValue > */
protected $match_Mod_typestack = array('Mod');
function match_Mod ($stack = array()) {
	$matchrule = "Mod"; $result = $this->construct($matchrule, $matchrule, null);
	$_411 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '%') {
			$this->pos += 1;
			$result["text"] .= '%';
		}
		else { $_411 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_411 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_411 = TRUE; break;
	}
	while(0);
	if( $_411 === TRUE ) { return $this->finalise($result); }
	if( $_411 === FALSE) { return FALSE; }
}


/* Product: NumericValue > ( Mul | Div | Mod )* */
protected $match_Product_typestack = array('Product');
function match_Product ($stack = array()) {
	$matchrule = "Product"; $result = $this->construct($matchrule, $matchrule, null);
	$_426 = NULL;
	do {
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_426 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_425 = $result;
			$pos_425 = $this->pos;
			$_424 = NULL;
			do {
				$_422 = NULL;
				do {
					$res_415 = $result;
					$pos_415 = $this->pos;
					$matcher = 'match_'.'Mul'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_422 = TRUE; break;
					}
					$result = $res_415;
					$this->pos = $pos_415;
					$_420 = NULL;
					do {
						$res_417 = $result;
						$pos_417 = $this->pos;
						$matcher = 'match_'.'Div'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_420 = TRUE; break;
						}
						$result = $res_417;
						$this->pos = $pos_417;
						$matcher = 'match_'.'Mod'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_420 = TRUE; break;
						}
						$result = $res_417;
						$this->pos = $pos_417;
						$_420 = FALSE; break;
					}
					while(0);
					if( $_420 === TRUE ) { $_422 = TRUE; break; }
					$result = $res_415;
					$this->pos = $pos_415;
					$_422 = FALSE; break;
				}
				while(0);
				if( $_422 === FALSE) { $_424 = FALSE; break; }
				$_424 = TRUE; break;
			}
			while(0);
			if( $_424 === FALSE) {
				$result = $res_425;
				$this->pos = $pos_425;
				unset( $res_425 );
				unset( $pos_425 );
				break;
			}
		}
		$_426 = TRUE; break;
	}
	while(0);
	if( $_426 === TRUE ) { return $this->finalise($result); }
	if( $_426 === FALSE) { return FALSE; }
}

public function Product_NumericValue ( &$result, $sub ) {
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
	$_430 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_430 = FALSE; break; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_430 = FALSE; break; }
		$_430 = TRUE; break;
	}
	while(0);
	if( $_430 === TRUE ) { return $this->finalise($result); }
	if( $_430 === FALSE) { return FALSE; }
}

public function MinusProduct_Product ( &$result, $sub ) {
		$result['expression'] = new ArithmeticUnaryExpression(
			ArithmeticUnaryExpression::TYPE_MINUS,
			$sub['operand']['expression']
		);
	}

/* Plus: '+' > second_operand:Product > */
protected $match_Plus_typestack = array('Plus');
function match_Plus ($stack = array()) {
	$matchrule = "Plus"; $result = $this->construct($matchrule, $matchrule, null);
	$_436 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '+') {
			$this->pos += 1;
			$result["text"] .= '+';
		}
		else { $_436 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_436 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_436 = TRUE; break;
	}
	while(0);
	if( $_436 === TRUE ) { return $this->finalise($result); }
	if( $_436 === FALSE) { return FALSE; }
}


/* Minus: '-' > second_operand:Product > */
protected $match_Minus_typestack = array('Minus');
function match_Minus ($stack = array()) {
	$matchrule = "Minus"; $result = $this->construct($matchrule, $matchrule, null);
	$_442 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_442 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_442 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_442 = TRUE; break;
	}
	while(0);
	if( $_442 === TRUE ) { return $this->finalise($result); }
	if( $_442 === FALSE) { return FALSE; }
}


/* Sum: ( MinusProduct | Product ) > ( Plus | Minus )* */
protected $match_Sum_typestack = array('Sum');
function match_Sum ($stack = array()) {
	$matchrule = "Sum"; $result = $this->construct($matchrule, $matchrule, null);
	$_459 = NULL;
	do {
		$_449 = NULL;
		do {
			$_447 = NULL;
			do {
				$res_444 = $result;
				$pos_444 = $this->pos;
				$matcher = 'match_'.'MinusProduct'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_447 = TRUE; break;
				}
				$result = $res_444;
				$this->pos = $pos_444;
				$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_447 = TRUE; break;
				}
				$result = $res_444;
				$this->pos = $pos_444;
				$_447 = FALSE; break;
			}
			while(0);
			if( $_447 === FALSE) { $_449 = FALSE; break; }
			$_449 = TRUE; break;
		}
		while(0);
		if( $_449 === FALSE) { $_459 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_458 = $result;
			$pos_458 = $this->pos;
			$_457 = NULL;
			do {
				$_455 = NULL;
				do {
					$res_452 = $result;
					$pos_452 = $this->pos;
					$matcher = 'match_'.'Plus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_455 = TRUE; break;
					}
					$result = $res_452;
					$this->pos = $pos_452;
					$matcher = 'match_'.'Minus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_455 = TRUE; break;
					}
					$result = $res_452;
					$this->pos = $pos_452;
					$_455 = FALSE; break;
				}
				while(0);
				if( $_455 === FALSE) { $_457 = FALSE; break; }
				$_457 = TRUE; break;
			}
			while(0);
			if( $_457 === FALSE) {
				$result = $res_458;
				$this->pos = $pos_458;
				unset( $res_458 );
				unset( $pos_458 );
				break;
			}
		}
		$_459 = TRUE; break;
	}
	while(0);
	if( $_459 === TRUE ) { return $this->finalise($result); }
	if( $_459 === FALSE) { return FALSE; }
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

/* NumericExpression: Sum > */
protected $match_NumericExpression_typestack = array('NumericExpression');
function match_NumericExpression ($stack = array()) {
	$matchrule = "NumericExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_463 = NULL;
	do {
		$matcher = 'match_'.'Sum'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_463 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_463 = TRUE; break;
	}
	while(0);
	if( $_463 === TRUE ) { return $this->finalise($result); }
	if( $_463 === FALSE) { return FALSE; }
}

public function NumericExpression_Sum ( &$result, $sub ) {
		$result['expression'] = $sub['sum'];
	}

/* StringExpression: String */
protected $match_StringExpression_typestack = array('StringExpression');
function match_StringExpression ($stack = array()) {
	$matchrule = "StringExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
	$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
	if ($subres !== FALSE) {
		$this->store( $result, $subres );
		return $this->finalise($result);
	}
	else { return FALSE; }
}

public function StringExpression_String ( &$result, $sub ) {
		$result['expression'] = new StringConstExpression($sub['val']['text']);
	}

/* Expression: StringExpression > | NumericExpression !BooleanBinaryOperatorSign | BooleanExpression */
protected $match_Expression_typestack = array('Expression');
function match_Expression ($stack = array()) {
	$matchrule = "Expression"; $result = $this->construct($matchrule, $matchrule, null);
	$_479 = NULL;
	do {
		$res_466 = $result;
		$pos_466 = $this->pos;
		$_469 = NULL;
		do {
			$matcher = 'match_'.'StringExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_469 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_469 = TRUE; break;
		}
		while(0);
		if( $_469 === TRUE ) { $_479 = TRUE; break; }
		$result = $res_466;
		$this->pos = $pos_466;
		$_477 = NULL;
		do {
			$res_471 = $result;
			$pos_471 = $this->pos;
			$_474 = NULL;
			do {
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_474 = FALSE; break; }
				$res_473 = $result;
				$pos_473 = $this->pos;
				$matcher = 'match_'.'BooleanBinaryOperatorSign'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$result = $res_473;
					$this->pos = $pos_473;
					$_474 = FALSE; break;
				}
				else {
					$result = $res_473;
					$this->pos = $pos_473;
				}
				$_474 = TRUE; break;
			}
			while(0);
			if( $_474 === TRUE ) { $_477 = TRUE; break; }
			$result = $res_471;
			$this->pos = $pos_471;
			$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_477 = TRUE; break;
			}
			$result = $res_471;
			$this->pos = $pos_471;
			$_477 = FALSE; break;
		}
		while(0);
		if( $_477 === TRUE ) { $_479 = TRUE; break; }
		$result = $res_466;
		$this->pos = $pos_466;
		$_479 = FALSE; break;
	}
	while(0);
	if( $_479 === TRUE ) { return $this->finalise($result); }
	if( $_479 === FALSE) { return FALSE; }
}

public function Expression_NumericExpression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

public function Expression_BooleanExpression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

public function Expression_StringExpression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* ArrayExpression: Function > | Variable > */
protected $match_ArrayExpression_typestack = array('ArrayExpression');
function match_ArrayExpression ($stack = array()) {
	$matchrule = "ArrayExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_490 = NULL;
	do {
		$res_481 = $result;
		$pos_481 = $this->pos;
		$_484 = NULL;
		do {
			$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_484 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_484 = TRUE; break;
		}
		while(0);
		if( $_484 === TRUE ) { $_490 = TRUE; break; }
		$result = $res_481;
		$this->pos = $pos_481;
		$_488 = NULL;
		do {
			$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_488 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_488 = TRUE; break;
		}
		while(0);
		if( $_488 === TRUE ) { $_490 = TRUE; break; }
		$result = $res_481;
		$this->pos = $pos_481;
		$_490 = FALSE; break;
	}
	while(0);
	if( $_490 === TRUE ) { return $this->finalise($result); }
	if( $_490 === FALSE) { return FALSE; }
}

public function ArrayExpression_Function ( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}

public function ArrayExpression_Variable ( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}



}