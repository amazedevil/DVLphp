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
		$result['validation'] = new ForeachValidation([ $sub['validation'] ]);
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

/* ValidationControl: Group | Ternary | Use | Validation */
protected $match_ValidationControl_typestack = array('ValidationControl');
function match_ValidationControl ($stack = array()) {
	$matchrule = "ValidationControl"; $result = $this->construct($matchrule, $matchrule, null);
	$_161 = NULL;
	do {
		$res_150 = $result;
		$pos_150 = $this->pos;
		$matcher = 'match_'.'Group'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_161 = TRUE; break;
		}
		$result = $res_150;
		$this->pos = $pos_150;
		$_159 = NULL;
		do {
			$res_152 = $result;
			$pos_152 = $this->pos;
			$matcher = 'match_'.'Ternary'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_159 = TRUE; break;
			}
			$result = $res_152;
			$this->pos = $pos_152;
			$_157 = NULL;
			do {
				$res_154 = $result;
				$pos_154 = $this->pos;
				$matcher = 'match_'.'Use'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_157 = TRUE; break;
				}
				$result = $res_154;
				$this->pos = $pos_154;
				$matcher = 'match_'.'Validation'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_157 = TRUE; break;
				}
				$result = $res_154;
				$this->pos = $pos_154;
				$_157 = FALSE; break;
			}
			while(0);
			if( $_157 === TRUE ) { $_159 = TRUE; break; }
			$result = $res_152;
			$this->pos = $pos_152;
			$_159 = FALSE; break;
		}
		while(0);
		if( $_159 === TRUE ) { $_161 = TRUE; break; }
		$result = $res_150;
		$this->pos = $pos_150;
		$_161 = FALSE; break;
	}
	while(0);
	if( $_161 === TRUE ) { return $this->finalise($result); }
	if( $_161 === FALSE) { return FALSE; }
}

public function ValidationControl_STR ( &$result, $sub ) {
		$result['validation'] = $sub['validation'];
	}

/* Validation: Expression ( > '@' > String > ( ':' > Tag )? )? > */
protected $match_Validation_typestack = array('Validation');
function match_Validation ($stack = array()) {
	$matchrule = "Validation"; $result = $this->construct($matchrule, $matchrule, null);
	$_177 = NULL;
	do {
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_177 = FALSE; break; }
		$res_175 = $result;
		$pos_175 = $this->pos;
		$_174 = NULL;
		do {
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			if (substr($this->string,$this->pos,1) == '@') {
				$this->pos += 1;
				$result["text"] .= '@';
			}
			else { $_174 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_174 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$res_173 = $result;
			$pos_173 = $this->pos;
			$_172 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ':') {
					$this->pos += 1;
					$result["text"] .= ':';
				}
				else { $_172 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Tag'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_172 = FALSE; break; }
				$_172 = TRUE; break;
			}
			while(0);
			if( $_172 === FALSE) {
				$result = $res_173;
				$this->pos = $pos_173;
				unset( $res_173 );
				unset( $pos_173 );
			}
			$_174 = TRUE; break;
		}
		while(0);
		if( $_174 === FALSE) {
			$result = $res_175;
			$this->pos = $pos_175;
			unset( $res_175 );
			unset( $pos_175 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_177 = TRUE; break;
	}
	while(0);
	if( $_177 === TRUE ) { return $this->finalise($result); }
	if( $_177 === FALSE) { return FALSE; }
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
	$_206 = NULL;
	do {
		$res_179 = $result;
		$pos_179 = $this->pos;
		$_182 = NULL;
		do {
			$matcher = 'match_'.'Boolean'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_182 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_182 = TRUE; break;
		}
		while(0);
		if( $_182 === TRUE ) { $_206 = TRUE; break; }
		$result = $res_179;
		$this->pos = $pos_179;
		$_204 = NULL;
		do {
			$res_184 = $result;
			$pos_184 = $this->pos;
			$_187 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_187 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_187 = TRUE; break;
			}
			while(0);
			if( $_187 === TRUE ) { $_204 = TRUE; break; }
			$result = $res_184;
			$this->pos = $pos_184;
			$_202 = NULL;
			do {
				$res_189 = $result;
				$pos_189 = $this->pos;
				$_192 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_192 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_192 = TRUE; break;
				}
				while(0);
				if( $_192 === TRUE ) { $_202 = TRUE; break; }
				$result = $res_189;
				$this->pos = $pos_189;
				$_200 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_200 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_200 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_200 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_200 = TRUE; break;
				}
				while(0);
				if( $_200 === TRUE ) { $_202 = TRUE; break; }
				$result = $res_189;
				$this->pos = $pos_189;
				$_202 = FALSE; break;
			}
			while(0);
			if( $_202 === TRUE ) { $_204 = TRUE; break; }
			$result = $res_184;
			$this->pos = $pos_184;
			$_204 = FALSE; break;
		}
		while(0);
		if( $_204 === TRUE ) { $_206 = TRUE; break; }
		$result = $res_179;
		$this->pos = $pos_179;
		$_206 = FALSE; break;
	}
	while(0);
	if( $_206 === TRUE ) { return $this->finalise($result); }
	if( $_206 === FALSE) { return FALSE; }
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
	$_218 = NULL;
	do {
		$res_208 = $result;
		$pos_208 = $this->pos;
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_218 = TRUE; break;
		}
		$result = $res_208;
		$this->pos = $pos_208;
		$_216 = NULL;
		do {
			$res_210 = $result;
			$pos_210 = $this->pos;
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_216 = TRUE; break;
			}
			$result = $res_210;
			$this->pos = $pos_210;
			$_214 = NULL;
			do {
				$matcher = 'match_'.'StringExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_214 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_214 = TRUE; break;
			}
			while(0);
			if( $_214 === TRUE ) { $_216 = TRUE; break; }
			$result = $res_210;
			$this->pos = $pos_210;
			$_216 = FALSE; break;
		}
		while(0);
		if( $_216 === TRUE ) { $_218 = TRUE; break; }
		$result = $res_208;
		$this->pos = $pos_208;
		$_218 = FALSE; break;
	}
	while(0);
	if( $_218 === TRUE ) { return $this->finalise($result); }
	if( $_218 === FALSE) { return FALSE; }
}

public function EqualityComparableExpression_STR ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* Greater: '>' > second_operand:NumericExpression > */
protected $match_Greater_typestack = array('Greater');
function match_Greater ($stack = array()) {
	$matchrule = "Greater"; $result = $this->construct($matchrule, $matchrule, null);
	$_224 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
		}
		else { $_224 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_224 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_224 = TRUE; break;
	}
	while(0);
	if( $_224 === TRUE ) { return $this->finalise($result); }
	if( $_224 === FALSE) { return FALSE; }
}


/* Less: '<' > second_operand:NumericExpression > */
protected $match_Less_typestack = array('Less');
function match_Less ($stack = array()) {
	$matchrule = "Less"; $result = $this->construct($matchrule, $matchrule, null);
	$_230 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '<') {
			$this->pos += 1;
			$result["text"] .= '<';
		}
		else { $_230 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_230 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_230 = TRUE; break;
	}
	while(0);
	if( $_230 === TRUE ) { return $this->finalise($result); }
	if( $_230 === FALSE) { return FALSE; }
}


/* LessOrEqual: '<=' > second_operand:NumericExpression > */
protected $match_LessOrEqual_typestack = array('LessOrEqual');
function match_LessOrEqual ($stack = array()) {
	$matchrule = "LessOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_236 = NULL;
	do {
		if (( $subres = $this->literal( '<=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_236 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_236 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_236 = TRUE; break;
	}
	while(0);
	if( $_236 === TRUE ) { return $this->finalise($result); }
	if( $_236 === FALSE) { return FALSE; }
}


/* GreaterOrEqual: '>=' > second_operand:NumericExpression > */
protected $match_GreaterOrEqual_typestack = array('GreaterOrEqual');
function match_GreaterOrEqual ($stack = array()) {
	$matchrule = "GreaterOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_242 = NULL;
	do {
		if (( $subres = $this->literal( '>=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_242 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_242 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_242 = TRUE; break;
	}
	while(0);
	if( $_242 === TRUE ) { return $this->finalise($result); }
	if( $_242 === FALSE) { return FALSE; }
}


/* Equal: '==' > second_operand:EqualityComparableExpression > */
protected $match_Equal_typestack = array('Equal');
function match_Equal ($stack = array()) {
	$matchrule = "Equal"; $result = $this->construct($matchrule, $matchrule, null);
	$_248 = NULL;
	do {
		if (( $subres = $this->literal( '==' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_248 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_248 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_248 = TRUE; break;
	}
	while(0);
	if( $_248 === TRUE ) { return $this->finalise($result); }
	if( $_248 === FALSE) { return FALSE; }
}


/* NotEqual: '!=' > second_operand:EqualityComparableExpression > */
protected $match_NotEqual_typestack = array('NotEqual');
function match_NotEqual ($stack = array()) {
	$matchrule = "NotEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_254 = NULL;
	do {
		if (( $subres = $this->literal( '!=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_254 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_254 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_254 = TRUE; break;
	}
	while(0);
	if( $_254 === TRUE ) { return $this->finalise($result); }
	if( $_254 === FALSE) { return FALSE; }
}


/* And: '&&' > second_operand:BooleanValue > */
protected $match_And_typestack = array('And');
function match_And ($stack = array()) {
	$matchrule = "And"; $result = $this->construct($matchrule, $matchrule, null);
	$_260 = NULL;
	do {
		if (( $subres = $this->literal( '&&' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_260 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_260 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_260 = TRUE; break;
	}
	while(0);
	if( $_260 === TRUE ) { return $this->finalise($result); }
	if( $_260 === FALSE) { return FALSE; }
}


/* Or: '||' > second_operand:BooleanValue > */
protected $match_Or_typestack = array('Or');
function match_Or ($stack = array()) {
	$matchrule = "Or"; $result = $this->construct($matchrule, $matchrule, null);
	$_266 = NULL;
	do {
		if (( $subres = $this->literal( '||' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_266 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_266 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_266 = TRUE; break;
	}
	while(0);
	if( $_266 === TRUE ) { return $this->finalise($result); }
	if( $_266 === FALSE) { return FALSE; }
}


/* Not: '!' BooleanValue > */
protected $match_Not_typestack = array('Not');
function match_Not ($stack = array()) {
	$matchrule = "Not"; $result = $this->construct($matchrule, $matchrule, null);
	$_271 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '!') {
			$this->pos += 1;
			$result["text"] .= '!';
		}
		else { $_271 = FALSE; break; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_271 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_271 = TRUE; break;
	}
	while(0);
	if( $_271 === TRUE ) { return $this->finalise($result); }
	if( $_271 === FALSE) { return FALSE; }
}

public function Not_BooleanValue ( &$result, $sub ) {
		$result['expression'] = new BooleanUnaryExpression(BooleanUnaryExpression::TYPE_NOT, $sub['expression']);
	}

/* BooleanBinaryOperatorSign: '>' | '<' | '>=' | '<=' | '==' | '!=' | '&&' | '||' */
protected $match_BooleanBinaryOperatorSign_typestack = array('BooleanBinaryOperatorSign');
function match_BooleanBinaryOperatorSign ($stack = array()) {
	$matchrule = "BooleanBinaryOperatorSign"; $result = $this->construct($matchrule, $matchrule, null);
	$_300 = NULL;
	do {
		$res_273 = $result;
		$pos_273 = $this->pos;
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
			$_300 = TRUE; break;
		}
		$result = $res_273;
		$this->pos = $pos_273;
		$_298 = NULL;
		do {
			$res_275 = $result;
			$pos_275 = $this->pos;
			if (substr($this->string,$this->pos,1) == '<') {
				$this->pos += 1;
				$result["text"] .= '<';
				$_298 = TRUE; break;
			}
			$result = $res_275;
			$this->pos = $pos_275;
			$_296 = NULL;
			do {
				$res_277 = $result;
				$pos_277 = $this->pos;
				if (( $subres = $this->literal( '>=' ) ) !== FALSE) {
					$result["text"] .= $subres;
					$_296 = TRUE; break;
				}
				$result = $res_277;
				$this->pos = $pos_277;
				$_294 = NULL;
				do {
					$res_279 = $result;
					$pos_279 = $this->pos;
					if (( $subres = $this->literal( '<=' ) ) !== FALSE) {
						$result["text"] .= $subres;
						$_294 = TRUE; break;
					}
					$result = $res_279;
					$this->pos = $pos_279;
					$_292 = NULL;
					do {
						$res_281 = $result;
						$pos_281 = $this->pos;
						if (( $subres = $this->literal( '==' ) ) !== FALSE) {
							$result["text"] .= $subres;
							$_292 = TRUE; break;
						}
						$result = $res_281;
						$this->pos = $pos_281;
						$_290 = NULL;
						do {
							$res_283 = $result;
							$pos_283 = $this->pos;
							if (( $subres = $this->literal( '!=' ) ) !== FALSE) {
								$result["text"] .= $subres;
								$_290 = TRUE; break;
							}
							$result = $res_283;
							$this->pos = $pos_283;
							$_288 = NULL;
							do {
								$res_285 = $result;
								$pos_285 = $this->pos;
								if (( $subres = $this->literal( '&&' ) ) !== FALSE) {
									$result["text"] .= $subres;
									$_288 = TRUE; break;
								}
								$result = $res_285;
								$this->pos = $pos_285;
								if (( $subres = $this->literal( '||' ) ) !== FALSE) {
									$result["text"] .= $subres;
									$_288 = TRUE; break;
								}
								$result = $res_285;
								$this->pos = $pos_285;
								$_288 = FALSE; break;
							}
							while(0);
							if( $_288 === TRUE ) { $_290 = TRUE; break; }
							$result = $res_283;
							$this->pos = $pos_283;
							$_290 = FALSE; break;
						}
						while(0);
						if( $_290 === TRUE ) { $_292 = TRUE; break; }
						$result = $res_281;
						$this->pos = $pos_281;
						$_292 = FALSE; break;
					}
					while(0);
					if( $_292 === TRUE ) { $_294 = TRUE; break; }
					$result = $res_279;
					$this->pos = $pos_279;
					$_294 = FALSE; break;
				}
				while(0);
				if( $_294 === TRUE ) { $_296 = TRUE; break; }
				$result = $res_277;
				$this->pos = $pos_277;
				$_296 = FALSE; break;
			}
			while(0);
			if( $_296 === TRUE ) { $_298 = TRUE; break; }
			$result = $res_275;
			$this->pos = $pos_275;
			$_298 = FALSE; break;
		}
		while(0);
		if( $_298 === TRUE ) { $_300 = TRUE; break; }
		$result = $res_273;
		$this->pos = $pos_273;
		$_300 = FALSE; break;
	}
	while(0);
	if( $_300 === TRUE ) { return $this->finalise($result); }
	if( $_300 === FALSE) { return FALSE; }
}


/* BooleanOperation: Not | NumericExpression > ( Greater | Less | LessOrEqual | GreaterOrEqual ) | EqualityComparableExpression > ( Equal | NotEqual ) | BooleanValue > ( And | Or ) > */
protected $match_BooleanOperation_typestack = array('BooleanOperation');
function match_BooleanOperation ($stack = array()) {
	$matchrule = "BooleanOperation"; $result = $this->construct($matchrule, $matchrule, null);
	$_352 = NULL;
	do {
		$res_302 = $result;
		$pos_302 = $this->pos;
		$matcher = 'match_'.'Not'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_352 = TRUE; break;
		}
		$result = $res_302;
		$this->pos = $pos_302;
		$_350 = NULL;
		do {
			$res_304 = $result;
			$pos_304 = $this->pos;
			$_322 = NULL;
			do {
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_322 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_320 = NULL;
				do {
					$_318 = NULL;
					do {
						$res_307 = $result;
						$pos_307 = $this->pos;
						$matcher = 'match_'.'Greater'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_318 = TRUE; break;
						}
						$result = $res_307;
						$this->pos = $pos_307;
						$_316 = NULL;
						do {
							$res_309 = $result;
							$pos_309 = $this->pos;
							$matcher = 'match_'.'Less'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_316 = TRUE; break;
							}
							$result = $res_309;
							$this->pos = $pos_309;
							$_314 = NULL;
							do {
								$res_311 = $result;
								$pos_311 = $this->pos;
								$matcher = 'match_'.'LessOrEqual'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_314 = TRUE; break;
								}
								$result = $res_311;
								$this->pos = $pos_311;
								$matcher = 'match_'.'GreaterOrEqual'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_314 = TRUE; break;
								}
								$result = $res_311;
								$this->pos = $pos_311;
								$_314 = FALSE; break;
							}
							while(0);
							if( $_314 === TRUE ) { $_316 = TRUE; break; }
							$result = $res_309;
							$this->pos = $pos_309;
							$_316 = FALSE; break;
						}
						while(0);
						if( $_316 === TRUE ) { $_318 = TRUE; break; }
						$result = $res_307;
						$this->pos = $pos_307;
						$_318 = FALSE; break;
					}
					while(0);
					if( $_318 === FALSE) { $_320 = FALSE; break; }
					$_320 = TRUE; break;
				}
				while(0);
				if( $_320 === FALSE) { $_322 = FALSE; break; }
				$_322 = TRUE; break;
			}
			while(0);
			if( $_322 === TRUE ) { $_350 = TRUE; break; }
			$result = $res_304;
			$this->pos = $pos_304;
			$_348 = NULL;
			do {
				$res_324 = $result;
				$pos_324 = $this->pos;
				$_334 = NULL;
				do {
					$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_334 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_332 = NULL;
					do {
						$_330 = NULL;
						do {
							$res_327 = $result;
							$pos_327 = $this->pos;
							$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_330 = TRUE; break;
							}
							$result = $res_327;
							$this->pos = $pos_327;
							$matcher = 'match_'.'NotEqual'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_330 = TRUE; break;
							}
							$result = $res_327;
							$this->pos = $pos_327;
							$_330 = FALSE; break;
						}
						while(0);
						if( $_330 === FALSE) { $_332 = FALSE; break; }
						$_332 = TRUE; break;
					}
					while(0);
					if( $_332 === FALSE) { $_334 = FALSE; break; }
					$_334 = TRUE; break;
				}
				while(0);
				if( $_334 === TRUE ) { $_348 = TRUE; break; }
				$result = $res_324;
				$this->pos = $pos_324;
				$_346 = NULL;
				do {
					$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_346 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_343 = NULL;
					do {
						$_341 = NULL;
						do {
							$res_338 = $result;
							$pos_338 = $this->pos;
							$matcher = 'match_'.'And'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_341 = TRUE; break;
							}
							$result = $res_338;
							$this->pos = $pos_338;
							$matcher = 'match_'.'Or'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_341 = TRUE; break;
							}
							$result = $res_338;
							$this->pos = $pos_338;
							$_341 = FALSE; break;
						}
						while(0);
						if( $_341 === FALSE) { $_343 = FALSE; break; }
						$_343 = TRUE; break;
					}
					while(0);
					if( $_343 === FALSE) { $_346 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_346 = TRUE; break;
				}
				while(0);
				if( $_346 === TRUE ) { $_348 = TRUE; break; }
				$result = $res_324;
				$this->pos = $pos_324;
				$_348 = FALSE; break;
			}
			while(0);
			if( $_348 === TRUE ) { $_350 = TRUE; break; }
			$result = $res_304;
			$this->pos = $pos_304;
			$_350 = FALSE; break;
		}
		while(0);
		if( $_350 === TRUE ) { $_352 = TRUE; break; }
		$result = $res_302;
		$this->pos = $pos_302;
		$_352 = FALSE; break;
	}
	while(0);
	if( $_352 === TRUE ) { return $this->finalise($result); }
	if( $_352 === FALSE) { return FALSE; }
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
	$_360 = NULL;
	do {
		$res_354 = $result;
		$pos_354 = $this->pos;
		$matcher = 'match_'.'BooleanOperation'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_360 = TRUE; break;
		}
		$result = $res_354;
		$this->pos = $pos_354;
		$_358 = NULL;
		do {
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_358 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_358 = TRUE; break;
		}
		while(0);
		if( $_358 === TRUE ) { $_360 = TRUE; break; }
		$result = $res_354;
		$this->pos = $pos_354;
		$_360 = FALSE; break;
	}
	while(0);
	if( $_360 === TRUE ) { return $this->finalise($result); }
	if( $_360 === FALSE) { return FALSE; }
}

public function BooleanExpression_STR ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* NumericValue: Number > | Function > | Variable > | '(' > NumericExpression > ')' > */
protected $match_NumericValue_typestack = array('NumericValue');
function match_NumericValue ($stack = array()) {
	$matchrule = "NumericValue"; $result = $this->construct($matchrule, $matchrule, null);
	$_389 = NULL;
	do {
		$res_362 = $result;
		$pos_362 = $this->pos;
		$_365 = NULL;
		do {
			$matcher = 'match_'.'Number'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_365 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_365 = TRUE; break;
		}
		while(0);
		if( $_365 === TRUE ) { $_389 = TRUE; break; }
		$result = $res_362;
		$this->pos = $pos_362;
		$_387 = NULL;
		do {
			$res_367 = $result;
			$pos_367 = $this->pos;
			$_370 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_370 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_370 = TRUE; break;
			}
			while(0);
			if( $_370 === TRUE ) { $_387 = TRUE; break; }
			$result = $res_367;
			$this->pos = $pos_367;
			$_385 = NULL;
			do {
				$res_372 = $result;
				$pos_372 = $this->pos;
				$_375 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_375 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_375 = TRUE; break;
				}
				while(0);
				if( $_375 === TRUE ) { $_385 = TRUE; break; }
				$result = $res_372;
				$this->pos = $pos_372;
				$_383 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_383 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_383 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_383 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_383 = TRUE; break;
				}
				while(0);
				if( $_383 === TRUE ) { $_385 = TRUE; break; }
				$result = $res_372;
				$this->pos = $pos_372;
				$_385 = FALSE; break;
			}
			while(0);
			if( $_385 === TRUE ) { $_387 = TRUE; break; }
			$result = $res_367;
			$this->pos = $pos_367;
			$_387 = FALSE; break;
		}
		while(0);
		if( $_387 === TRUE ) { $_389 = TRUE; break; }
		$result = $res_362;
		$this->pos = $pos_362;
		$_389 = FALSE; break;
	}
	while(0);
	if( $_389 === TRUE ) { return $this->finalise($result); }
	if( $_389 === FALSE) { return FALSE; }
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
	$_395 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '*') {
			$this->pos += 1;
			$result["text"] .= '*';
		}
		else { $_395 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_395 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_395 = TRUE; break;
	}
	while(0);
	if( $_395 === TRUE ) { return $this->finalise($result); }
	if( $_395 === FALSE) { return FALSE; }
}


/* Div: '/' > second_operand:NumericValue > */
protected $match_Div_typestack = array('Div');
function match_Div ($stack = array()) {
	$matchrule = "Div"; $result = $this->construct($matchrule, $matchrule, null);
	$_401 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '/') {
			$this->pos += 1;
			$result["text"] .= '/';
		}
		else { $_401 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_401 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_401 = TRUE; break;
	}
	while(0);
	if( $_401 === TRUE ) { return $this->finalise($result); }
	if( $_401 === FALSE) { return FALSE; }
}


/* Mod: '%' > second_operand:NumericValue > */
protected $match_Mod_typestack = array('Mod');
function match_Mod ($stack = array()) {
	$matchrule = "Mod"; $result = $this->construct($matchrule, $matchrule, null);
	$_407 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '%') {
			$this->pos += 1;
			$result["text"] .= '%';
		}
		else { $_407 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_407 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_407 = TRUE; break;
	}
	while(0);
	if( $_407 === TRUE ) { return $this->finalise($result); }
	if( $_407 === FALSE) { return FALSE; }
}


/* Product: NumericValue > ( Mul | Div | Mod )* */
protected $match_Product_typestack = array('Product');
function match_Product ($stack = array()) {
	$matchrule = "Product"; $result = $this->construct($matchrule, $matchrule, null);
	$_422 = NULL;
	do {
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_422 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_421 = $result;
			$pos_421 = $this->pos;
			$_420 = NULL;
			do {
				$_418 = NULL;
				do {
					$res_411 = $result;
					$pos_411 = $this->pos;
					$matcher = 'match_'.'Mul'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_418 = TRUE; break;
					}
					$result = $res_411;
					$this->pos = $pos_411;
					$_416 = NULL;
					do {
						$res_413 = $result;
						$pos_413 = $this->pos;
						$matcher = 'match_'.'Div'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_416 = TRUE; break;
						}
						$result = $res_413;
						$this->pos = $pos_413;
						$matcher = 'match_'.'Mod'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_416 = TRUE; break;
						}
						$result = $res_413;
						$this->pos = $pos_413;
						$_416 = FALSE; break;
					}
					while(0);
					if( $_416 === TRUE ) { $_418 = TRUE; break; }
					$result = $res_411;
					$this->pos = $pos_411;
					$_418 = FALSE; break;
				}
				while(0);
				if( $_418 === FALSE) { $_420 = FALSE; break; }
				$_420 = TRUE; break;
			}
			while(0);
			if( $_420 === FALSE) {
				$result = $res_421;
				$this->pos = $pos_421;
				unset( $res_421 );
				unset( $pos_421 );
				break;
			}
		}
		$_422 = TRUE; break;
	}
	while(0);
	if( $_422 === TRUE ) { return $this->finalise($result); }
	if( $_422 === FALSE) { return FALSE; }
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
	$_426 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_426 = FALSE; break; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_426 = FALSE; break; }
		$_426 = TRUE; break;
	}
	while(0);
	if( $_426 === TRUE ) { return $this->finalise($result); }
	if( $_426 === FALSE) { return FALSE; }
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
	$_432 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '+') {
			$this->pos += 1;
			$result["text"] .= '+';
		}
		else { $_432 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_432 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_432 = TRUE; break;
	}
	while(0);
	if( $_432 === TRUE ) { return $this->finalise($result); }
	if( $_432 === FALSE) { return FALSE; }
}


/* Minus: '-' > second_operand:Product > */
protected $match_Minus_typestack = array('Minus');
function match_Minus ($stack = array()) {
	$matchrule = "Minus"; $result = $this->construct($matchrule, $matchrule, null);
	$_438 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_438 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_438 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_438 = TRUE; break;
	}
	while(0);
	if( $_438 === TRUE ) { return $this->finalise($result); }
	if( $_438 === FALSE) { return FALSE; }
}


/* Sum: ( MinusProduct | Product ) > ( Plus | Minus )* */
protected $match_Sum_typestack = array('Sum');
function match_Sum ($stack = array()) {
	$matchrule = "Sum"; $result = $this->construct($matchrule, $matchrule, null);
	$_455 = NULL;
	do {
		$_445 = NULL;
		do {
			$_443 = NULL;
			do {
				$res_440 = $result;
				$pos_440 = $this->pos;
				$matcher = 'match_'.'MinusProduct'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_443 = TRUE; break;
				}
				$result = $res_440;
				$this->pos = $pos_440;
				$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_443 = TRUE; break;
				}
				$result = $res_440;
				$this->pos = $pos_440;
				$_443 = FALSE; break;
			}
			while(0);
			if( $_443 === FALSE) { $_445 = FALSE; break; }
			$_445 = TRUE; break;
		}
		while(0);
		if( $_445 === FALSE) { $_455 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_454 = $result;
			$pos_454 = $this->pos;
			$_453 = NULL;
			do {
				$_451 = NULL;
				do {
					$res_448 = $result;
					$pos_448 = $this->pos;
					$matcher = 'match_'.'Plus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_451 = TRUE; break;
					}
					$result = $res_448;
					$this->pos = $pos_448;
					$matcher = 'match_'.'Minus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_451 = TRUE; break;
					}
					$result = $res_448;
					$this->pos = $pos_448;
					$_451 = FALSE; break;
				}
				while(0);
				if( $_451 === FALSE) { $_453 = FALSE; break; }
				$_453 = TRUE; break;
			}
			while(0);
			if( $_453 === FALSE) {
				$result = $res_454;
				$this->pos = $pos_454;
				unset( $res_454 );
				unset( $pos_454 );
				break;
			}
		}
		$_455 = TRUE; break;
	}
	while(0);
	if( $_455 === TRUE ) { return $this->finalise($result); }
	if( $_455 === FALSE) { return FALSE; }
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
	$_459 = NULL;
	do {
		$matcher = 'match_'.'Sum'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_459 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_459 = TRUE; break;
	}
	while(0);
	if( $_459 === TRUE ) { return $this->finalise($result); }
	if( $_459 === FALSE) { return FALSE; }
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
	$_475 = NULL;
	do {
		$res_462 = $result;
		$pos_462 = $this->pos;
		$_465 = NULL;
		do {
			$matcher = 'match_'.'StringExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_465 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_465 = TRUE; break;
		}
		while(0);
		if( $_465 === TRUE ) { $_475 = TRUE; break; }
		$result = $res_462;
		$this->pos = $pos_462;
		$_473 = NULL;
		do {
			$res_467 = $result;
			$pos_467 = $this->pos;
			$_470 = NULL;
			do {
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_470 = FALSE; break; }
				$res_469 = $result;
				$pos_469 = $this->pos;
				$matcher = 'match_'.'BooleanBinaryOperatorSign'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$result = $res_469;
					$this->pos = $pos_469;
					$_470 = FALSE; break;
				}
				else {
					$result = $res_469;
					$this->pos = $pos_469;
				}
				$_470 = TRUE; break;
			}
			while(0);
			if( $_470 === TRUE ) { $_473 = TRUE; break; }
			$result = $res_467;
			$this->pos = $pos_467;
			$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_473 = TRUE; break;
			}
			$result = $res_467;
			$this->pos = $pos_467;
			$_473 = FALSE; break;
		}
		while(0);
		if( $_473 === TRUE ) { $_475 = TRUE; break; }
		$result = $res_462;
		$this->pos = $pos_462;
		$_475 = FALSE; break;
	}
	while(0);
	if( $_475 === TRUE ) { return $this->finalise($result); }
	if( $_475 === FALSE) { return FALSE; }
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
	$_486 = NULL;
	do {
		$res_477 = $result;
		$pos_477 = $this->pos;
		$_480 = NULL;
		do {
			$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_480 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_480 = TRUE; break;
		}
		while(0);
		if( $_480 === TRUE ) { $_486 = TRUE; break; }
		$result = $res_477;
		$this->pos = $pos_477;
		$_484 = NULL;
		do {
			$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_484 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_484 = TRUE; break;
		}
		while(0);
		if( $_484 === TRUE ) { $_486 = TRUE; break; }
		$result = $res_477;
		$this->pos = $pos_477;
		$_486 = FALSE; break;
	}
	while(0);
	if( $_486 === TRUE ) { return $this->finalise($result); }
	if( $_486 === FALSE) { return FALSE; }
}

public function ArrayExpression_Function ( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}

public function ArrayExpression_Variable ( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}



}