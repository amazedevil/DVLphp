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
	
	function getStringValue($text) {
		return substr($text, 1, strlen($text) - 2);
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


/* String: /'([^'\\]|\\')*'/ | /"([^"\\]|\\")*"/ */
protected $match_String_typestack = array('String');
function match_String ($stack = array()) {
	$matchrule = "String"; $result = $this->construct($matchrule, $matchrule, null);
	$_4 = NULL;
	do {
		$res_1 = $result;
		$pos_1 = $this->pos;
		if (( $subres = $this->rx( '/\'([^\'\\\\]|\\\\\')*\'/' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$_4 = TRUE; break;
		}
		$result = $res_1;
		$this->pos = $pos_1;
		if (( $subres = $this->rx( '/"([^"\\\\]|\\\\")*"/' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$_4 = TRUE; break;
		}
		$result = $res_1;
		$this->pos = $pos_1;
		$_4 = FALSE; break;
	}
	while(0);
	if( $_4 === TRUE ) { return $this->finalise($result); }
	if( $_4 === FALSE) { return FALSE; }
}


/* Name: !Boolean !This /[a-zA-Z_]+([a-zA-Z0-9_]*)/ */
protected $match_Name_typestack = array('Name');
function match_Name ($stack = array()) {
	$matchrule = "Name"; $result = $this->construct($matchrule, $matchrule, null);
	$_9 = NULL;
	do {
		$res_6 = $result;
		$pos_6 = $this->pos;
		$matcher = 'match_'.'Boolean'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$result = $res_6;
			$this->pos = $pos_6;
			$_9 = FALSE; break;
		}
		else {
			$result = $res_6;
			$this->pos = $pos_6;
		}
		$res_7 = $result;
		$pos_7 = $this->pos;
		$matcher = 'match_'.'This'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$result = $res_7;
			$this->pos = $pos_7;
			$_9 = FALSE; break;
		}
		else {
			$result = $res_7;
			$this->pos = $pos_7;
		}
		if (( $subres = $this->rx( '/[a-zA-Z_]+([a-zA-Z0-9_]*)/' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_9 = FALSE; break; }
		$_9 = TRUE; break;
	}
	while(0);
	if( $_9 === TRUE ) { return $this->finalise($result); }
	if( $_9 === FALSE) { return FALSE; }
}


/* Tag: !Boolean !This /[a-zA-Z0-9_]+/ */
protected $match_Tag_typestack = array('Tag');
function match_Tag ($stack = array()) {
	$matchrule = "Tag"; $result = $this->construct($matchrule, $matchrule, null);
	$_14 = NULL;
	do {
		$res_11 = $result;
		$pos_11 = $this->pos;
		$matcher = 'match_'.'Boolean'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$result = $res_11;
			$this->pos = $pos_11;
			$_14 = FALSE; break;
		}
		else {
			$result = $res_11;
			$this->pos = $pos_11;
		}
		$res_12 = $result;
		$pos_12 = $this->pos;
		$matcher = 'match_'.'This'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$result = $res_12;
			$this->pos = $pos_12;
			$_14 = FALSE; break;
		}
		else {
			$result = $res_12;
			$this->pos = $pos_12;
		}
		if (( $subres = $this->rx( '/[a-zA-Z0-9_]+/' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_14 = FALSE; break; }
		$_14 = TRUE; break;
	}
	while(0);
	if( $_14 === TRUE ) { return $this->finalise($result); }
	if( $_14 === FALSE) { return FALSE; }
}


/* Boolean: 'true' | 'false' */
protected $match_Boolean_typestack = array('Boolean');
function match_Boolean ($stack = array()) {
	$matchrule = "Boolean"; $result = $this->construct($matchrule, $matchrule, null);
	$_19 = NULL;
	do {
		$res_16 = $result;
		$pos_16 = $this->pos;
		if (( $subres = $this->literal( 'true' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$_19 = TRUE; break;
		}
		$result = $res_16;
		$this->pos = $pos_16;
		if (( $subres = $this->literal( 'false' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$_19 = TRUE; break;
		}
		$result = $res_16;
		$this->pos = $pos_16;
		$_19 = FALSE; break;
	}
	while(0);
	if( $_19 === TRUE ) { return $this->finalise($result); }
	if( $_19 === FALSE) { return FALSE; }
}


/* This: 'this' !/[a-zA-Z0-9_]/ */
protected $match_This_typestack = array('This');
function match_This ($stack = array()) {
	$matchrule = "This"; $result = $this->construct($matchrule, $matchrule, null);
	$_23 = NULL;
	do {
		if (( $subres = $this->literal( 'this' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_23 = FALSE; break; }
		$res_22 = $result;
		$pos_22 = $this->pos;
		if (( $subres = $this->rx( '/[a-zA-Z0-9_]/' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$result = $res_22;
			$this->pos = $pos_22;
			$_23 = FALSE; break;
		}
		else {
			$result = $res_22;
			$this->pos = $pos_22;
		}
		$_23 = TRUE; break;
	}
	while(0);
	if( $_23 === TRUE ) { return $this->finalise($result); }
	if( $_23 === FALSE) { return FALSE; }
}


/* Property: '.' Name > */
protected $match_Property_typestack = array('Property');
function match_Property ($stack = array()) {
	$matchrule = "Property"; $result = $this->construct($matchrule, $matchrule, null);
	$_28 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '.') {
			$this->pos += 1;
			$result["text"] .= '.';
		}
		else { $_28 = FALSE; break; }
		$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_28 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_28 = TRUE; break;
	}
	while(0);
	if( $_28 === TRUE ) { return $this->finalise($result); }
	if( $_28 === FALSE) { return FALSE; }
}

public function Property_Name ( &$result, $sub ) {
		$result['accessor'] = new PropertyAccessor($sub['text']);
	}

/* ArrayElement: '[' > ( selector:Expression )? > ']' > */
protected $match_ArrayElement_typestack = array('ArrayElement');
function match_ArrayElement ($stack = array()) {
	$matchrule = "ArrayElement"; $result = $this->construct($matchrule, $matchrule, null);
	$_38 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '[') {
			$this->pos += 1;
			$result["text"] .= '[';
		}
		else { $_38 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_34 = $result;
		$pos_34 = $this->pos;
		$_33 = NULL;
		do {
			$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres, "selector" );
			}
			else { $_33 = FALSE; break; }
			$_33 = TRUE; break;
		}
		while(0);
		if( $_33 === FALSE) {
			$result = $res_34;
			$this->pos = $pos_34;
			unset( $res_34 );
			unset( $pos_34 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ']') {
			$this->pos += 1;
			$result["text"] .= ']';
		}
		else { $_38 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_38 = TRUE; break;
	}
	while(0);
	if( $_38 === TRUE ) { return $this->finalise($result); }
	if( $_38 === FALSE) { return FALSE; }
}


/* Variable: ( Name | This ) (Property | ArrayElement)* > */
protected $match_Variable_typestack = array('Variable');
function match_Variable ($stack = array()) {
	$matchrule = "Variable"; $result = $this->construct($matchrule, $matchrule, null);
	$_55 = NULL;
	do {
		$_45 = NULL;
		do {
			$_43 = NULL;
			do {
				$res_40 = $result;
				$pos_40 = $this->pos;
				$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_43 = TRUE; break;
				}
				$result = $res_40;
				$this->pos = $pos_40;
				$matcher = 'match_'.'This'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_43 = TRUE; break;
				}
				$result = $res_40;
				$this->pos = $pos_40;
				$_43 = FALSE; break;
			}
			while(0);
			if( $_43 === FALSE) { $_45 = FALSE; break; }
			$_45 = TRUE; break;
		}
		while(0);
		if( $_45 === FALSE) { $_55 = FALSE; break; }
		while (true) {
			$res_53 = $result;
			$pos_53 = $this->pos;
			$_52 = NULL;
			do {
				$_50 = NULL;
				do {
					$res_47 = $result;
					$pos_47 = $this->pos;
					$matcher = 'match_'.'Property'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_50 = TRUE; break;
					}
					$result = $res_47;
					$this->pos = $pos_47;
					$matcher = 'match_'.'ArrayElement'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_50 = TRUE; break;
					}
					$result = $res_47;
					$this->pos = $pos_47;
					$_50 = FALSE; break;
				}
				while(0);
				if( $_50 === FALSE) { $_52 = FALSE; break; }
				$_52 = TRUE; break;
			}
			while(0);
			if( $_52 === FALSE) {
				$result = $res_53;
				$this->pos = $pos_53;
				unset( $res_53 );
				unset( $pos_53 );
				break;
			}
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_55 = TRUE; break;
	}
	while(0);
	if( $_55 === TRUE ) { return $this->finalise($result); }
	if( $_55 === FALSE) { return FALSE; }
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
	$_72 = NULL;
	do {
		$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_72 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_72 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_62 = $result;
		$pos_62 = $this->pos;
		$_61 = NULL;
		do {
			$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_61 = FALSE; break; }
			$_61 = TRUE; break;
		}
		while(0);
		if( $_61 === FALSE) {
			$result = $res_62;
			$this->pos = $pos_62;
			unset( $res_62 );
			unset( $pos_62 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_69 = $result;
			$pos_69 = $this->pos;
			$_68 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_68 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_68 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_68 = TRUE; break;
			}
			while(0);
			if( $_68 === FALSE) {
				$result = $res_69;
				$this->pos = $pos_69;
				unset( $res_69 );
				unset( $pos_69 );
				break;
			}
		}
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_72 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_72 = TRUE; break;
	}
	while(0);
	if( $_72 === TRUE ) { return $this->finalise($result); }
	if( $_72 === FALSE) { return FALSE; }
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
	$_82 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_82 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_82 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_82 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_82 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_82 = TRUE; break;
	}
	while(0);
	if( $_82 === TRUE ) { return $this->finalise($result); }
	if( $_82 === FALSE) { return FALSE; }
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
	$_100 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_100 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
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
		if (substr($this->string,$this->pos,1) == '?') {
			$this->pos += 1;
			$result["text"] .= '?';
		}
		else { $_100 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_100 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_99 = $result;
		$pos_99 = $this->pos;
		$_98 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == ':') {
				$this->pos += 1;
				$result["text"] .= ':';
			}
			else { $_98 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_98 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_98 = TRUE; break;
		}
		while(0);
		if( $_98 === FALSE) {
			$result = $res_99;
			$this->pos = $pos_99;
			unset( $res_99 );
			unset( $pos_99 );
		}
		$_100 = TRUE; break;
	}
	while(0);
	if( $_100 === TRUE ) { return $this->finalise($result); }
	if( $_100 === FALSE) { return FALSE; }
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
	$_122 = NULL;
	do {
		if (( $subres = $this->literal( '$(' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_122 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ArrayExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_122 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_116 = $result;
		$pos_116 = $this->pos;
		$_115 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == ':') {
				$this->pos += 1;
				$result["text"] .= ':';
			}
			else { $_115 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_115 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$res_114 = $result;
			$pos_114 = $this->pos;
			$_113 = NULL;
			do {
				if (( $subres = $this->literal( '=>' ) ) !== FALSE) { $result["text"] .= $subres; }
				else { $_113 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_113 = FALSE; break; }
				$_113 = TRUE; break;
			}
			while(0);
			if( $_113 === FALSE) {
				$result = $res_114;
				$this->pos = $pos_114;
				unset( $res_114 );
				unset( $pos_114 );
			}
			$_115 = TRUE; break;
		}
		while(0);
		if( $_115 === FALSE) {
			$result = $res_116;
			$this->pos = $pos_116;
			unset( $res_116 );
			unset( $pos_116 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_122 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_122 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_122 = TRUE; break;
	}
	while(0);
	if( $_122 === TRUE ) { return $this->finalise($result); }
	if( $_122 === FALSE) { return FALSE; }
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
	$_136 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '{') {
			$this->pos += 1;
			$result["text"] .= '{';
		}
		else { $_136 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_136 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_133 = $result;
			$pos_133 = $this->pos;
			$_132 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_132 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_132 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_132 = TRUE; break;
			}
			while(0);
			if( $_132 === FALSE) {
				$result = $res_133;
				$this->pos = $pos_133;
				unset( $res_133 );
				unset( $pos_133 );
				break;
			}
		}
		if (substr($this->string,$this->pos,1) == '}') {
			$this->pos += 1;
			$result["text"] .= '}';
		}
		else { $_136 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_136 = TRUE; break;
	}
	while(0);
	if( $_136 === TRUE ) { return $this->finalise($result); }
	if( $_136 === FALSE) { return FALSE; }
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
	$_149 = NULL;
	do {
		$res_138 = $result;
		$pos_138 = $this->pos;
		$matcher = 'match_'.'Group'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_149 = TRUE; break;
		}
		$result = $res_138;
		$this->pos = $pos_138;
		$_147 = NULL;
		do {
			$res_140 = $result;
			$pos_140 = $this->pos;
			$matcher = 'match_'.'Ternary'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_147 = TRUE; break;
			}
			$result = $res_140;
			$this->pos = $pos_140;
			$_145 = NULL;
			do {
				$res_142 = $result;
				$pos_142 = $this->pos;
				$matcher = 'match_'.'Use'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_145 = TRUE; break;
				}
				$result = $res_142;
				$this->pos = $pos_142;
				$matcher = 'match_'.'Validation'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_145 = TRUE; break;
				}
				$result = $res_142;
				$this->pos = $pos_142;
				$_145 = FALSE; break;
			}
			while(0);
			if( $_145 === TRUE ) { $_147 = TRUE; break; }
			$result = $res_140;
			$this->pos = $pos_140;
			$_147 = FALSE; break;
		}
		while(0);
		if( $_147 === TRUE ) { $_149 = TRUE; break; }
		$result = $res_138;
		$this->pos = $pos_138;
		$_149 = FALSE; break;
	}
	while(0);
	if( $_149 === TRUE ) { return $this->finalise($result); }
	if( $_149 === FALSE) { return FALSE; }
}

public function ValidationControl_STR ( &$result, $sub ) {
		$result['validation'] = $sub['validation'];
	}

/* Validation: Expression ( > '@' > String > ( ':' > Tag )? )? > */
protected $match_Validation_typestack = array('Validation');
function match_Validation ($stack = array()) {
	$matchrule = "Validation"; $result = $this->construct($matchrule, $matchrule, null);
	$_165 = NULL;
	do {
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_165 = FALSE; break; }
		$res_163 = $result;
		$pos_163 = $this->pos;
		$_162 = NULL;
		do {
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			if (substr($this->string,$this->pos,1) == '@') {
				$this->pos += 1;
				$result["text"] .= '@';
			}
			else { $_162 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_162 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$res_161 = $result;
			$pos_161 = $this->pos;
			$_160 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ':') {
					$this->pos += 1;
					$result["text"] .= ':';
				}
				else { $_160 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Tag'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_160 = FALSE; break; }
				$_160 = TRUE; break;
			}
			while(0);
			if( $_160 === FALSE) {
				$result = $res_161;
				$this->pos = $pos_161;
				unset( $res_161 );
				unset( $pos_161 );
			}
			$_162 = TRUE; break;
		}
		while(0);
		if( $_162 === FALSE) {
			$result = $res_163;
			$this->pos = $pos_163;
			unset( $res_163 );
			unset( $pos_163 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_165 = TRUE; break;
	}
	while(0);
	if( $_165 === TRUE ) { return $this->finalise($result); }
	if( $_165 === FALSE) { return FALSE; }
}

public function Validation_Expression ( &$result, $sub ) {
		$result['validation'] = new Validation( $sub['expression'] );
	}

public function Validation_String ( &$result, $sub ) {
		$result['validation']->setMessage($this->getStringValue($sub['text']));
	}

public function Validation_Tag ( &$result, $sub ) {
		echo "Validation - Tag\n";
		$result['validation']->setTag($sub['text']);
	}

/* BooleanValue: Boolean > | Function > | Variable > | '(' > BooleanExpression > ')' > */
protected $match_BooleanValue_typestack = array('BooleanValue');
function match_BooleanValue ($stack = array()) {
	$matchrule = "BooleanValue"; $result = $this->construct($matchrule, $matchrule, null);
	$_194 = NULL;
	do {
		$res_167 = $result;
		$pos_167 = $this->pos;
		$_170 = NULL;
		do {
			$matcher = 'match_'.'Boolean'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_170 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_170 = TRUE; break;
		}
		while(0);
		if( $_170 === TRUE ) { $_194 = TRUE; break; }
		$result = $res_167;
		$this->pos = $pos_167;
		$_192 = NULL;
		do {
			$res_172 = $result;
			$pos_172 = $this->pos;
			$_175 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_175 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_175 = TRUE; break;
			}
			while(0);
			if( $_175 === TRUE ) { $_192 = TRUE; break; }
			$result = $res_172;
			$this->pos = $pos_172;
			$_190 = NULL;
			do {
				$res_177 = $result;
				$pos_177 = $this->pos;
				$_180 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_180 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_180 = TRUE; break;
				}
				while(0);
				if( $_180 === TRUE ) { $_190 = TRUE; break; }
				$result = $res_177;
				$this->pos = $pos_177;
				$_188 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_188 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_188 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_188 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_188 = TRUE; break;
				}
				while(0);
				if( $_188 === TRUE ) { $_190 = TRUE; break; }
				$result = $res_177;
				$this->pos = $pos_177;
				$_190 = FALSE; break;
			}
			while(0);
			if( $_190 === TRUE ) { $_192 = TRUE; break; }
			$result = $res_172;
			$this->pos = $pos_172;
			$_192 = FALSE; break;
		}
		while(0);
		if( $_192 === TRUE ) { $_194 = TRUE; break; }
		$result = $res_167;
		$this->pos = $pos_167;
		$_194 = FALSE; break;
	}
	while(0);
	if( $_194 === TRUE ) { return $this->finalise($result); }
	if( $_194 === FALSE) { return FALSE; }
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
	$_206 = NULL;
	do {
		$res_196 = $result;
		$pos_196 = $this->pos;
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_206 = TRUE; break;
		}
		$result = $res_196;
		$this->pos = $pos_196;
		$_204 = NULL;
		do {
			$res_198 = $result;
			$pos_198 = $this->pos;
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_204 = TRUE; break;
			}
			$result = $res_198;
			$this->pos = $pos_198;
			$_202 = NULL;
			do {
				$matcher = 'match_'.'StringExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_202 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_202 = TRUE; break;
			}
			while(0);
			if( $_202 === TRUE ) { $_204 = TRUE; break; }
			$result = $res_198;
			$this->pos = $pos_198;
			$_204 = FALSE; break;
		}
		while(0);
		if( $_204 === TRUE ) { $_206 = TRUE; break; }
		$result = $res_196;
		$this->pos = $pos_196;
		$_206 = FALSE; break;
	}
	while(0);
	if( $_206 === TRUE ) { return $this->finalise($result); }
	if( $_206 === FALSE) { return FALSE; }
}

public function EqualityComparableExpression_STR ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* Greater: '>' > second_operand:NumericExpression > */
protected $match_Greater_typestack = array('Greater');
function match_Greater ($stack = array()) {
	$matchrule = "Greater"; $result = $this->construct($matchrule, $matchrule, null);
	$_212 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
		}
		else { $_212 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_212 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_212 = TRUE; break;
	}
	while(0);
	if( $_212 === TRUE ) { return $this->finalise($result); }
	if( $_212 === FALSE) { return FALSE; }
}


/* Less: '<' > second_operand:NumericExpression > */
protected $match_Less_typestack = array('Less');
function match_Less ($stack = array()) {
	$matchrule = "Less"; $result = $this->construct($matchrule, $matchrule, null);
	$_218 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '<') {
			$this->pos += 1;
			$result["text"] .= '<';
		}
		else { $_218 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_218 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_218 = TRUE; break;
	}
	while(0);
	if( $_218 === TRUE ) { return $this->finalise($result); }
	if( $_218 === FALSE) { return FALSE; }
}


/* LessOrEqual: '<=' > second_operand:NumericExpression > */
protected $match_LessOrEqual_typestack = array('LessOrEqual');
function match_LessOrEqual ($stack = array()) {
	$matchrule = "LessOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_224 = NULL;
	do {
		if (( $subres = $this->literal( '<=' ) ) !== FALSE) { $result["text"] .= $subres; }
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


/* GreaterOrEqual: '>=' > second_operand:NumericExpression > */
protected $match_GreaterOrEqual_typestack = array('GreaterOrEqual');
function match_GreaterOrEqual ($stack = array()) {
	$matchrule = "GreaterOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_230 = NULL;
	do {
		if (( $subres = $this->literal( '>=' ) ) !== FALSE) { $result["text"] .= $subres; }
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


/* Equal: '==' > second_operand:EqualityComparableExpression > */
protected $match_Equal_typestack = array('Equal');
function match_Equal ($stack = array()) {
	$matchrule = "Equal"; $result = $this->construct($matchrule, $matchrule, null);
	$_236 = NULL;
	do {
		if (( $subres = $this->literal( '==' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_236 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
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


/* NotEqual: '!=' > second_operand:EqualityComparableExpression > */
protected $match_NotEqual_typestack = array('NotEqual');
function match_NotEqual ($stack = array()) {
	$matchrule = "NotEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_242 = NULL;
	do {
		if (( $subres = $this->literal( '!=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_242 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
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


/* And: '&&' > second_operand:BooleanValue > */
protected $match_And_typestack = array('And');
function match_And ($stack = array()) {
	$matchrule = "And"; $result = $this->construct($matchrule, $matchrule, null);
	$_248 = NULL;
	do {
		if (( $subres = $this->literal( '&&' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_248 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
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


/* Or: '||' > second_operand:BooleanValue > */
protected $match_Or_typestack = array('Or');
function match_Or ($stack = array()) {
	$matchrule = "Or"; $result = $this->construct($matchrule, $matchrule, null);
	$_254 = NULL;
	do {
		if (( $subres = $this->literal( '||' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_254 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
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


/* Not: '!' BooleanValue > */
protected $match_Not_typestack = array('Not');
function match_Not ($stack = array()) {
	$matchrule = "Not"; $result = $this->construct($matchrule, $matchrule, null);
	$_259 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '!') {
			$this->pos += 1;
			$result["text"] .= '!';
		}
		else { $_259 = FALSE; break; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_259 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_259 = TRUE; break;
	}
	while(0);
	if( $_259 === TRUE ) { return $this->finalise($result); }
	if( $_259 === FALSE) { return FALSE; }
}

public function Not_BooleanValue ( &$result, $sub ) {
		$result['expression'] = new BooleanUnaryExpression(BooleanUnaryExpression::TYPE_NOT, $sub['expression']);
	}

/* BooleanBinaryOperatorSign: '>' | '<' | '>=' | '<=' | '==' | '!=' | '&&' | '||' */
protected $match_BooleanBinaryOperatorSign_typestack = array('BooleanBinaryOperatorSign');
function match_BooleanBinaryOperatorSign ($stack = array()) {
	$matchrule = "BooleanBinaryOperatorSign"; $result = $this->construct($matchrule, $matchrule, null);
	$_288 = NULL;
	do {
		$res_261 = $result;
		$pos_261 = $this->pos;
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
			$_288 = TRUE; break;
		}
		$result = $res_261;
		$this->pos = $pos_261;
		$_286 = NULL;
		do {
			$res_263 = $result;
			$pos_263 = $this->pos;
			if (substr($this->string,$this->pos,1) == '<') {
				$this->pos += 1;
				$result["text"] .= '<';
				$_286 = TRUE; break;
			}
			$result = $res_263;
			$this->pos = $pos_263;
			$_284 = NULL;
			do {
				$res_265 = $result;
				$pos_265 = $this->pos;
				if (( $subres = $this->literal( '>=' ) ) !== FALSE) {
					$result["text"] .= $subres;
					$_284 = TRUE; break;
				}
				$result = $res_265;
				$this->pos = $pos_265;
				$_282 = NULL;
				do {
					$res_267 = $result;
					$pos_267 = $this->pos;
					if (( $subres = $this->literal( '<=' ) ) !== FALSE) {
						$result["text"] .= $subres;
						$_282 = TRUE; break;
					}
					$result = $res_267;
					$this->pos = $pos_267;
					$_280 = NULL;
					do {
						$res_269 = $result;
						$pos_269 = $this->pos;
						if (( $subres = $this->literal( '==' ) ) !== FALSE) {
							$result["text"] .= $subres;
							$_280 = TRUE; break;
						}
						$result = $res_269;
						$this->pos = $pos_269;
						$_278 = NULL;
						do {
							$res_271 = $result;
							$pos_271 = $this->pos;
							if (( $subres = $this->literal( '!=' ) ) !== FALSE) {
								$result["text"] .= $subres;
								$_278 = TRUE; break;
							}
							$result = $res_271;
							$this->pos = $pos_271;
							$_276 = NULL;
							do {
								$res_273 = $result;
								$pos_273 = $this->pos;
								if (( $subres = $this->literal( '&&' ) ) !== FALSE) {
									$result["text"] .= $subres;
									$_276 = TRUE; break;
								}
								$result = $res_273;
								$this->pos = $pos_273;
								if (( $subres = $this->literal( '||' ) ) !== FALSE) {
									$result["text"] .= $subres;
									$_276 = TRUE; break;
								}
								$result = $res_273;
								$this->pos = $pos_273;
								$_276 = FALSE; break;
							}
							while(0);
							if( $_276 === TRUE ) { $_278 = TRUE; break; }
							$result = $res_271;
							$this->pos = $pos_271;
							$_278 = FALSE; break;
						}
						while(0);
						if( $_278 === TRUE ) { $_280 = TRUE; break; }
						$result = $res_269;
						$this->pos = $pos_269;
						$_280 = FALSE; break;
					}
					while(0);
					if( $_280 === TRUE ) { $_282 = TRUE; break; }
					$result = $res_267;
					$this->pos = $pos_267;
					$_282 = FALSE; break;
				}
				while(0);
				if( $_282 === TRUE ) { $_284 = TRUE; break; }
				$result = $res_265;
				$this->pos = $pos_265;
				$_284 = FALSE; break;
			}
			while(0);
			if( $_284 === TRUE ) { $_286 = TRUE; break; }
			$result = $res_263;
			$this->pos = $pos_263;
			$_286 = FALSE; break;
		}
		while(0);
		if( $_286 === TRUE ) { $_288 = TRUE; break; }
		$result = $res_261;
		$this->pos = $pos_261;
		$_288 = FALSE; break;
	}
	while(0);
	if( $_288 === TRUE ) { return $this->finalise($result); }
	if( $_288 === FALSE) { return FALSE; }
}


/* BooleanOperation: Not | NumericExpression > ( Greater | Less | LessOrEqual | GreaterOrEqual ) | EqualityComparableExpression > ( Equal | NotEqual ) | BooleanValue > ( And | Or ) > */
protected $match_BooleanOperation_typestack = array('BooleanOperation');
function match_BooleanOperation ($stack = array()) {
	$matchrule = "BooleanOperation"; $result = $this->construct($matchrule, $matchrule, null);
	$_340 = NULL;
	do {
		$res_290 = $result;
		$pos_290 = $this->pos;
		$matcher = 'match_'.'Not'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_340 = TRUE; break;
		}
		$result = $res_290;
		$this->pos = $pos_290;
		$_338 = NULL;
		do {
			$res_292 = $result;
			$pos_292 = $this->pos;
			$_310 = NULL;
			do {
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_310 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_308 = NULL;
				do {
					$_306 = NULL;
					do {
						$res_295 = $result;
						$pos_295 = $this->pos;
						$matcher = 'match_'.'Greater'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_306 = TRUE; break;
						}
						$result = $res_295;
						$this->pos = $pos_295;
						$_304 = NULL;
						do {
							$res_297 = $result;
							$pos_297 = $this->pos;
							$matcher = 'match_'.'Less'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_304 = TRUE; break;
							}
							$result = $res_297;
							$this->pos = $pos_297;
							$_302 = NULL;
							do {
								$res_299 = $result;
								$pos_299 = $this->pos;
								$matcher = 'match_'.'LessOrEqual'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_302 = TRUE; break;
								}
								$result = $res_299;
								$this->pos = $pos_299;
								$matcher = 'match_'.'GreaterOrEqual'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_302 = TRUE; break;
								}
								$result = $res_299;
								$this->pos = $pos_299;
								$_302 = FALSE; break;
							}
							while(0);
							if( $_302 === TRUE ) { $_304 = TRUE; break; }
							$result = $res_297;
							$this->pos = $pos_297;
							$_304 = FALSE; break;
						}
						while(0);
						if( $_304 === TRUE ) { $_306 = TRUE; break; }
						$result = $res_295;
						$this->pos = $pos_295;
						$_306 = FALSE; break;
					}
					while(0);
					if( $_306 === FALSE) { $_308 = FALSE; break; }
					$_308 = TRUE; break;
				}
				while(0);
				if( $_308 === FALSE) { $_310 = FALSE; break; }
				$_310 = TRUE; break;
			}
			while(0);
			if( $_310 === TRUE ) { $_338 = TRUE; break; }
			$result = $res_292;
			$this->pos = $pos_292;
			$_336 = NULL;
			do {
				$res_312 = $result;
				$pos_312 = $this->pos;
				$_322 = NULL;
				do {
					$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_322 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_320 = NULL;
					do {
						$_318 = NULL;
						do {
							$res_315 = $result;
							$pos_315 = $this->pos;
							$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_318 = TRUE; break;
							}
							$result = $res_315;
							$this->pos = $pos_315;
							$matcher = 'match_'.'NotEqual'; $key = $matcher; $pos = $this->pos;
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
						if( $_318 === FALSE) { $_320 = FALSE; break; }
						$_320 = TRUE; break;
					}
					while(0);
					if( $_320 === FALSE) { $_322 = FALSE; break; }
					$_322 = TRUE; break;
				}
				while(0);
				if( $_322 === TRUE ) { $_336 = TRUE; break; }
				$result = $res_312;
				$this->pos = $pos_312;
				$_334 = NULL;
				do {
					$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_334 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_331 = NULL;
					do {
						$_329 = NULL;
						do {
							$res_326 = $result;
							$pos_326 = $this->pos;
							$matcher = 'match_'.'And'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_329 = TRUE; break;
							}
							$result = $res_326;
							$this->pos = $pos_326;
							$matcher = 'match_'.'Or'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_329 = TRUE; break;
							}
							$result = $res_326;
							$this->pos = $pos_326;
							$_329 = FALSE; break;
						}
						while(0);
						if( $_329 === FALSE) { $_331 = FALSE; break; }
						$_331 = TRUE; break;
					}
					while(0);
					if( $_331 === FALSE) { $_334 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_334 = TRUE; break;
				}
				while(0);
				if( $_334 === TRUE ) { $_336 = TRUE; break; }
				$result = $res_312;
				$this->pos = $pos_312;
				$_336 = FALSE; break;
			}
			while(0);
			if( $_336 === TRUE ) { $_338 = TRUE; break; }
			$result = $res_292;
			$this->pos = $pos_292;
			$_338 = FALSE; break;
		}
		while(0);
		if( $_338 === TRUE ) { $_340 = TRUE; break; }
		$result = $res_290;
		$this->pos = $pos_290;
		$_340 = FALSE; break;
	}
	while(0);
	if( $_340 === TRUE ) { return $this->finalise($result); }
	if( $_340 === FALSE) { return FALSE; }
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
	$_348 = NULL;
	do {
		$res_342 = $result;
		$pos_342 = $this->pos;
		$matcher = 'match_'.'BooleanOperation'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_348 = TRUE; break;
		}
		$result = $res_342;
		$this->pos = $pos_342;
		$_346 = NULL;
		do {
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_346 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_346 = TRUE; break;
		}
		while(0);
		if( $_346 === TRUE ) { $_348 = TRUE; break; }
		$result = $res_342;
		$this->pos = $pos_342;
		$_348 = FALSE; break;
	}
	while(0);
	if( $_348 === TRUE ) { return $this->finalise($result); }
	if( $_348 === FALSE) { return FALSE; }
}

public function BooleanExpression_STR ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* NumericValue: Number > | Function > | Variable > | '(' > NumericExpression > ')' > */
protected $match_NumericValue_typestack = array('NumericValue');
function match_NumericValue ($stack = array()) {
	$matchrule = "NumericValue"; $result = $this->construct($matchrule, $matchrule, null);
	$_377 = NULL;
	do {
		$res_350 = $result;
		$pos_350 = $this->pos;
		$_353 = NULL;
		do {
			$matcher = 'match_'.'Number'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_353 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_353 = TRUE; break;
		}
		while(0);
		if( $_353 === TRUE ) { $_377 = TRUE; break; }
		$result = $res_350;
		$this->pos = $pos_350;
		$_375 = NULL;
		do {
			$res_355 = $result;
			$pos_355 = $this->pos;
			$_358 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_358 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_358 = TRUE; break;
			}
			while(0);
			if( $_358 === TRUE ) { $_375 = TRUE; break; }
			$result = $res_355;
			$this->pos = $pos_355;
			$_373 = NULL;
			do {
				$res_360 = $result;
				$pos_360 = $this->pos;
				$_363 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_363 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_363 = TRUE; break;
				}
				while(0);
				if( $_363 === TRUE ) { $_373 = TRUE; break; }
				$result = $res_360;
				$this->pos = $pos_360;
				$_371 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_371 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_371 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_371 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_371 = TRUE; break;
				}
				while(0);
				if( $_371 === TRUE ) { $_373 = TRUE; break; }
				$result = $res_360;
				$this->pos = $pos_360;
				$_373 = FALSE; break;
			}
			while(0);
			if( $_373 === TRUE ) { $_375 = TRUE; break; }
			$result = $res_355;
			$this->pos = $pos_355;
			$_375 = FALSE; break;
		}
		while(0);
		if( $_375 === TRUE ) { $_377 = TRUE; break; }
		$result = $res_350;
		$this->pos = $pos_350;
		$_377 = FALSE; break;
	}
	while(0);
	if( $_377 === TRUE ) { return $this->finalise($result); }
	if( $_377 === FALSE) { return FALSE; }
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
	$_383 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '*') {
			$this->pos += 1;
			$result["text"] .= '*';
		}
		else { $_383 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_383 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_383 = TRUE; break;
	}
	while(0);
	if( $_383 === TRUE ) { return $this->finalise($result); }
	if( $_383 === FALSE) { return FALSE; }
}


/* Div: '/' > second_operand:NumericValue > */
protected $match_Div_typestack = array('Div');
function match_Div ($stack = array()) {
	$matchrule = "Div"; $result = $this->construct($matchrule, $matchrule, null);
	$_389 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '/') {
			$this->pos += 1;
			$result["text"] .= '/';
		}
		else { $_389 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_389 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_389 = TRUE; break;
	}
	while(0);
	if( $_389 === TRUE ) { return $this->finalise($result); }
	if( $_389 === FALSE) { return FALSE; }
}


/* Mod: '%' > second_operand:NumericValue > */
protected $match_Mod_typestack = array('Mod');
function match_Mod ($stack = array()) {
	$matchrule = "Mod"; $result = $this->construct($matchrule, $matchrule, null);
	$_395 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '%') {
			$this->pos += 1;
			$result["text"] .= '%';
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


/* Product: NumericValue > ( Mul | Div | Mod )* */
protected $match_Product_typestack = array('Product');
function match_Product ($stack = array()) {
	$matchrule = "Product"; $result = $this->construct($matchrule, $matchrule, null);
	$_410 = NULL;
	do {
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_410 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_409 = $result;
			$pos_409 = $this->pos;
			$_408 = NULL;
			do {
				$_406 = NULL;
				do {
					$res_399 = $result;
					$pos_399 = $this->pos;
					$matcher = 'match_'.'Mul'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_406 = TRUE; break;
					}
					$result = $res_399;
					$this->pos = $pos_399;
					$_404 = NULL;
					do {
						$res_401 = $result;
						$pos_401 = $this->pos;
						$matcher = 'match_'.'Div'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_404 = TRUE; break;
						}
						$result = $res_401;
						$this->pos = $pos_401;
						$matcher = 'match_'.'Mod'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_404 = TRUE; break;
						}
						$result = $res_401;
						$this->pos = $pos_401;
						$_404 = FALSE; break;
					}
					while(0);
					if( $_404 === TRUE ) { $_406 = TRUE; break; }
					$result = $res_399;
					$this->pos = $pos_399;
					$_406 = FALSE; break;
				}
				while(0);
				if( $_406 === FALSE) { $_408 = FALSE; break; }
				$_408 = TRUE; break;
			}
			while(0);
			if( $_408 === FALSE) {
				$result = $res_409;
				$this->pos = $pos_409;
				unset( $res_409 );
				unset( $pos_409 );
				break;
			}
		}
		$_410 = TRUE; break;
	}
	while(0);
	if( $_410 === TRUE ) { return $this->finalise($result); }
	if( $_410 === FALSE) { return FALSE; }
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
	$_414 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_414 = FALSE; break; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_414 = FALSE; break; }
		$_414 = TRUE; break;
	}
	while(0);
	if( $_414 === TRUE ) { return $this->finalise($result); }
	if( $_414 === FALSE) { return FALSE; }
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
	$_420 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '+') {
			$this->pos += 1;
			$result["text"] .= '+';
		}
		else { $_420 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_420 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_420 = TRUE; break;
	}
	while(0);
	if( $_420 === TRUE ) { return $this->finalise($result); }
	if( $_420 === FALSE) { return FALSE; }
}


/* Minus: '-' > second_operand:Product > */
protected $match_Minus_typestack = array('Minus');
function match_Minus ($stack = array()) {
	$matchrule = "Minus"; $result = $this->construct($matchrule, $matchrule, null);
	$_426 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_426 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_426 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_426 = TRUE; break;
	}
	while(0);
	if( $_426 === TRUE ) { return $this->finalise($result); }
	if( $_426 === FALSE) { return FALSE; }
}


/* Sum: ( MinusProduct | Product ) > ( Plus | Minus )* */
protected $match_Sum_typestack = array('Sum');
function match_Sum ($stack = array()) {
	$matchrule = "Sum"; $result = $this->construct($matchrule, $matchrule, null);
	$_443 = NULL;
	do {
		$_433 = NULL;
		do {
			$_431 = NULL;
			do {
				$res_428 = $result;
				$pos_428 = $this->pos;
				$matcher = 'match_'.'MinusProduct'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_431 = TRUE; break;
				}
				$result = $res_428;
				$this->pos = $pos_428;
				$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_431 = TRUE; break;
				}
				$result = $res_428;
				$this->pos = $pos_428;
				$_431 = FALSE; break;
			}
			while(0);
			if( $_431 === FALSE) { $_433 = FALSE; break; }
			$_433 = TRUE; break;
		}
		while(0);
		if( $_433 === FALSE) { $_443 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_442 = $result;
			$pos_442 = $this->pos;
			$_441 = NULL;
			do {
				$_439 = NULL;
				do {
					$res_436 = $result;
					$pos_436 = $this->pos;
					$matcher = 'match_'.'Plus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_439 = TRUE; break;
					}
					$result = $res_436;
					$this->pos = $pos_436;
					$matcher = 'match_'.'Minus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_439 = TRUE; break;
					}
					$result = $res_436;
					$this->pos = $pos_436;
					$_439 = FALSE; break;
				}
				while(0);
				if( $_439 === FALSE) { $_441 = FALSE; break; }
				$_441 = TRUE; break;
			}
			while(0);
			if( $_441 === FALSE) {
				$result = $res_442;
				$this->pos = $pos_442;
				unset( $res_442 );
				unset( $pos_442 );
				break;
			}
		}
		$_443 = TRUE; break;
	}
	while(0);
	if( $_443 === TRUE ) { return $this->finalise($result); }
	if( $_443 === FALSE) { return FALSE; }
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
	$_447 = NULL;
	do {
		$matcher = 'match_'.'Sum'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_447 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_447 = TRUE; break;
	}
	while(0);
	if( $_447 === TRUE ) { return $this->finalise($result); }
	if( $_447 === FALSE) { return FALSE; }
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
		$result['expression'] = new StringConstExpression($this->getStringValue($sub['text']));
	}

/* Expression: StringExpression > | NumericExpression !BooleanBinaryOperatorSign | BooleanExpression */
protected $match_Expression_typestack = array('Expression');
function match_Expression ($stack = array()) {
	$matchrule = "Expression"; $result = $this->construct($matchrule, $matchrule, null);
	$_463 = NULL;
	do {
		$res_450 = $result;
		$pos_450 = $this->pos;
		$_453 = NULL;
		do {
			$matcher = 'match_'.'StringExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_453 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_453 = TRUE; break;
		}
		while(0);
		if( $_453 === TRUE ) { $_463 = TRUE; break; }
		$result = $res_450;
		$this->pos = $pos_450;
		$_461 = NULL;
		do {
			$res_455 = $result;
			$pos_455 = $this->pos;
			$_458 = NULL;
			do {
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_458 = FALSE; break; }
				$res_457 = $result;
				$pos_457 = $this->pos;
				$matcher = 'match_'.'BooleanBinaryOperatorSign'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$result = $res_457;
					$this->pos = $pos_457;
					$_458 = FALSE; break;
				}
				else {
					$result = $res_457;
					$this->pos = $pos_457;
				}
				$_458 = TRUE; break;
			}
			while(0);
			if( $_458 === TRUE ) { $_461 = TRUE; break; }
			$result = $res_455;
			$this->pos = $pos_455;
			$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_461 = TRUE; break;
			}
			$result = $res_455;
			$this->pos = $pos_455;
			$_461 = FALSE; break;
		}
		while(0);
		if( $_461 === TRUE ) { $_463 = TRUE; break; }
		$result = $res_450;
		$this->pos = $pos_450;
		$_463 = FALSE; break;
	}
	while(0);
	if( $_463 === TRUE ) { return $this->finalise($result); }
	if( $_463 === FALSE) { return FALSE; }
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
	$_474 = NULL;
	do {
		$res_465 = $result;
		$pos_465 = $this->pos;
		$_468 = NULL;
		do {
			$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_468 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_468 = TRUE; break;
		}
		while(0);
		if( $_468 === TRUE ) { $_474 = TRUE; break; }
		$result = $res_465;
		$this->pos = $pos_465;
		$_472 = NULL;
		do {
			$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_472 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_472 = TRUE; break;
		}
		while(0);
		if( $_472 === TRUE ) { $_474 = TRUE; break; }
		$result = $res_465;
		$this->pos = $pos_465;
		$_474 = FALSE; break;
	}
	while(0);
	if( $_474 === TRUE ) { return $this->finalise($result); }
	if( $_474 === FALSE) { return FALSE; }
}

public function ArrayExpression_Function ( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}

public function ArrayExpression_Variable ( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}



}