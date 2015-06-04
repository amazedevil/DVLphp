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

/* ArrayElement: '[' > selector:Expression > ']' > */
protected $match_ArrayElement_typestack = array('ArrayElement');
function match_ArrayElement ($stack = array()) {
	$matchrule = "ArrayElement"; $result = $this->construct($matchrule, $matchrule, null);
	$_48 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '[') {
			$this->pos += 1;
			$result["text"] .= '[';
		}
		else { $_48 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "selector" );
		}
		else { $_48 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ']') {
			$this->pos += 1;
			$result["text"] .= ']';
		}
		else { $_48 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_48 = TRUE; break;
	}
	while(0);
	if( $_48 === TRUE ) { return $this->finalise($result); }
	if( $_48 === FALSE) { return FALSE; }
}


/* Variable: ( Name | This ) (Property | ArrayElement)* > */
protected $match_Variable_typestack = array('Variable');
function match_Variable ($stack = array()) {
	$matchrule = "Variable"; $result = $this->construct($matchrule, $matchrule, null);
	$_65 = NULL;
	do {
		$_55 = NULL;
		do {
			$_53 = NULL;
			do {
				$res_50 = $result;
				$pos_50 = $this->pos;
				$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_53 = TRUE; break;
				}
				$result = $res_50;
				$this->pos = $pos_50;
				$matcher = 'match_'.'This'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_53 = TRUE; break;
				}
				$result = $res_50;
				$this->pos = $pos_50;
				$_53 = FALSE; break;
			}
			while(0);
			if( $_53 === FALSE) { $_55 = FALSE; break; }
			$_55 = TRUE; break;
		}
		while(0);
		if( $_55 === FALSE) { $_65 = FALSE; break; }
		while (true) {
			$res_63 = $result;
			$pos_63 = $this->pos;
			$_62 = NULL;
			do {
				$_60 = NULL;
				do {
					$res_57 = $result;
					$pos_57 = $this->pos;
					$matcher = 'match_'.'Property'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_60 = TRUE; break;
					}
					$result = $res_57;
					$this->pos = $pos_57;
					$matcher = 'match_'.'ArrayElement'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_60 = TRUE; break;
					}
					$result = $res_57;
					$this->pos = $pos_57;
					$_60 = FALSE; break;
				}
				while(0);
				if( $_60 === FALSE) { $_62 = FALSE; break; }
				$_62 = TRUE; break;
			}
			while(0);
			if( $_62 === FALSE) {
				$result = $res_63;
				$this->pos = $pos_63;
				unset( $res_63 );
				unset( $pos_63 );
				break;
			}
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_65 = TRUE; break;
	}
	while(0);
	if( $_65 === TRUE ) { return $this->finalise($result); }
	if( $_65 === FALSE) { return FALSE; }
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
	$_82 = NULL;
	do {
		$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_82 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_82 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_72 = $result;
		$pos_72 = $this->pos;
		$_71 = NULL;
		do {
			$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_71 = FALSE; break; }
			$_71 = TRUE; break;
		}
		while(0);
		if( $_71 === FALSE) {
			$result = $res_72;
			$this->pos = $pos_72;
			unset( $res_72 );
			unset( $pos_72 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_79 = $result;
			$pos_79 = $this->pos;
			$_78 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_78 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_78 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_78 = TRUE; break;
			}
			while(0);
			if( $_78 === FALSE) {
				$result = $res_79;
				$this->pos = $pos_79;
				unset( $res_79 );
				unset( $pos_79 );
				break;
			}
		}
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_82 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_82 = TRUE; break;
	}
	while(0);
	if( $_82 === TRUE ) { return $this->finalise($result); }
	if( $_82 === FALSE) { return FALSE; }
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
	$_92 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_92 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_92 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_92 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_92 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_92 = TRUE; break;
	}
	while(0);
	if( $_92 === TRUE ) { return $this->finalise($result); }
	if( $_92 === FALSE) { return FALSE; }
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
	$_110 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_110 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_110 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_110 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == '?') {
			$this->pos += 1;
			$result["text"] .= '?';
		}
		else { $_110 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_110 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_109 = $result;
		$pos_109 = $this->pos;
		$_108 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == ':') {
				$this->pos += 1;
				$result["text"] .= ':';
			}
			else { $_108 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_108 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_108 = TRUE; break;
		}
		while(0);
		if( $_108 === FALSE) {
			$result = $res_109;
			$this->pos = $pos_109;
			unset( $res_109 );
			unset( $pos_109 );
		}
		$_110 = TRUE; break;
	}
	while(0);
	if( $_110 === TRUE ) { return $this->finalise($result); }
	if( $_110 === FALSE) { return FALSE; }
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

/* Foreach: '$(' > ArrayExpression > ( ':' > key_value:Name > ( '=>' > value:Name )? )? > ')' > ValidationControl > */
protected $match_Foreach_typestack = array('Foreach');
function match_Foreach ($stack = array()) {
	$matchrule = "Foreach"; $result = $this->construct($matchrule, $matchrule, null);
	$_132 = NULL;
	do {
		if (( $subres = $this->literal( '$(' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_132 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ArrayExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_132 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_126 = $result;
		$pos_126 = $this->pos;
		$_125 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == ':') {
				$this->pos += 1;
				$result["text"] .= ':';
			}
			else { $_125 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres, "key_value" );
			}
			else { $_125 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$res_124 = $result;
			$pos_124 = $this->pos;
			$_123 = NULL;
			do {
				if (( $subres = $this->literal( '=>' ) ) !== FALSE) { $result["text"] .= $subres; }
				else { $_123 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres, "value" );
				}
				else { $_123 = FALSE; break; }
				$_123 = TRUE; break;
			}
			while(0);
			if( $_123 === FALSE) {
				$result = $res_124;
				$this->pos = $pos_124;
				unset( $res_124 );
				unset( $pos_124 );
			}
			$_125 = TRUE; break;
		}
		while(0);
		if( $_125 === FALSE) {
			$result = $res_126;
			$this->pos = $pos_126;
			unset( $res_126 );
			unset( $pos_126 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
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
	if( $_132 === TRUE ) { return $this->finalise($result); }
	if( $_132 === FALSE) { return FALSE; }
}

public function Foreach_ArrayExpression ( &$result, $sub ) {
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
	$_146 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '{') {
			$this->pos += 1;
			$result["text"] .= '{';
		}
		else { $_146 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_146 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_143 = $result;
			$pos_143 = $this->pos;
			$_142 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_142 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_142 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_142 = TRUE; break;
			}
			while(0);
			if( $_142 === FALSE) {
				$result = $res_143;
				$this->pos = $pos_143;
				unset( $res_143 );
				unset( $pos_143 );
				break;
			}
		}
		if (substr($this->string,$this->pos,1) == '}') {
			$this->pos += 1;
			$result["text"] .= '}';
		}
		else { $_146 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_146 = TRUE; break;
	}
	while(0);
	if( $_146 === TRUE ) { return $this->finalise($result); }
	if( $_146 === FALSE) { return FALSE; }
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
	$_163 = NULL;
	do {
		$res_148 = $result;
		$pos_148 = $this->pos;
		$matcher = 'match_'.'Group'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_163 = TRUE; break;
		}
		$result = $res_148;
		$this->pos = $pos_148;
		$_161 = NULL;
		do {
			$res_150 = $result;
			$pos_150 = $this->pos;
			$matcher = 'match_'.'Foreach'; $key = $matcher; $pos = $this->pos;
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
		if( $_161 === TRUE ) { $_163 = TRUE; break; }
		$result = $res_148;
		$this->pos = $pos_148;
		$_163 = FALSE; break;
	}
	while(0);
	if( $_163 === TRUE ) { return $this->finalise($result); }
	if( $_163 === FALSE) { return FALSE; }
}

public function ValidationControl_STR ( &$result, $sub ) {
		$result['validation'] = $sub['validation'];
	}

/* Validation: Expression ( > '@' > message:String > ( '%' > tag:String )? )? > */
protected $match_Validation_typestack = array('Validation');
function match_Validation ($stack = array()) {
	$matchrule = "Validation"; $result = $this->construct($matchrule, $matchrule, null);
	$_179 = NULL;
	do {
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_179 = FALSE; break; }
		$res_177 = $result;
		$pos_177 = $this->pos;
		$_176 = NULL;
		do {
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			if (substr($this->string,$this->pos,1) == '@') {
				$this->pos += 1;
				$result["text"] .= '@';
			}
			else { $_176 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres, "message" );
			}
			else { $_176 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$res_175 = $result;
			$pos_175 = $this->pos;
			$_174 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == '%') {
					$this->pos += 1;
					$result["text"] .= '%';
				}
				else { $_174 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres, "tag" );
				}
				else { $_174 = FALSE; break; }
				$_174 = TRUE; break;
			}
			while(0);
			if( $_174 === FALSE) {
				$result = $res_175;
				$this->pos = $pos_175;
				unset( $res_175 );
				unset( $pos_175 );
			}
			$_176 = TRUE; break;
		}
		while(0);
		if( $_176 === FALSE) {
			$result = $res_177;
			$this->pos = $pos_177;
			unset( $res_177 );
			unset( $pos_177 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_179 = TRUE; break;
	}
	while(0);
	if( $_179 === TRUE ) { return $this->finalise($result); }
	if( $_179 === FALSE) { return FALSE; }
}

public function Validation_Expression ( &$result, $sub ) {
		$result['validation'] = new Validation( $sub['expression'] );
	}

public function Validation_message ( &$result, $sub ) {
		$result['validation']->setMessage($sub['val']['text']);
	}

public function Validation_tag ( &$result, $sub ) {
		$result['validation']->setTag($sub['val']['text']);
	}

/* BooleanValue: Boolean > | Function > | Variable > | '(' > BooleanExpression > ')' > */
protected $match_BooleanValue_typestack = array('BooleanValue');
function match_BooleanValue ($stack = array()) {
	$matchrule = "BooleanValue"; $result = $this->construct($matchrule, $matchrule, null);
	$_208 = NULL;
	do {
		$res_181 = $result;
		$pos_181 = $this->pos;
		$_184 = NULL;
		do {
			$matcher = 'match_'.'Boolean'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_184 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_184 = TRUE; break;
		}
		while(0);
		if( $_184 === TRUE ) { $_208 = TRUE; break; }
		$result = $res_181;
		$this->pos = $pos_181;
		$_206 = NULL;
		do {
			$res_186 = $result;
			$pos_186 = $this->pos;
			$_189 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_189 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_189 = TRUE; break;
			}
			while(0);
			if( $_189 === TRUE ) { $_206 = TRUE; break; }
			$result = $res_186;
			$this->pos = $pos_186;
			$_204 = NULL;
			do {
				$res_191 = $result;
				$pos_191 = $this->pos;
				$_194 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_194 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_194 = TRUE; break;
				}
				while(0);
				if( $_194 === TRUE ) { $_204 = TRUE; break; }
				$result = $res_191;
				$this->pos = $pos_191;
				$_202 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_202 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_202 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_202 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_202 = TRUE; break;
				}
				while(0);
				if( $_202 === TRUE ) { $_204 = TRUE; break; }
				$result = $res_191;
				$this->pos = $pos_191;
				$_204 = FALSE; break;
			}
			while(0);
			if( $_204 === TRUE ) { $_206 = TRUE; break; }
			$result = $res_186;
			$this->pos = $pos_186;
			$_206 = FALSE; break;
		}
		while(0);
		if( $_206 === TRUE ) { $_208 = TRUE; break; }
		$result = $res_181;
		$this->pos = $pos_181;
		$_208 = FALSE; break;
	}
	while(0);
	if( $_208 === TRUE ) { return $this->finalise($result); }
	if( $_208 === FALSE) { return FALSE; }
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
	$_220 = NULL;
	do {
		$res_210 = $result;
		$pos_210 = $this->pos;
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_220 = TRUE; break;
		}
		$result = $res_210;
		$this->pos = $pos_210;
		$_218 = NULL;
		do {
			$res_212 = $result;
			$pos_212 = $this->pos;
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_218 = TRUE; break;
			}
			$result = $res_212;
			$this->pos = $pos_212;
			$_216 = NULL;
			do {
				$matcher = 'match_'.'StringExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_216 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_216 = TRUE; break;
			}
			while(0);
			if( $_216 === TRUE ) { $_218 = TRUE; break; }
			$result = $res_212;
			$this->pos = $pos_212;
			$_218 = FALSE; break;
		}
		while(0);
		if( $_218 === TRUE ) { $_220 = TRUE; break; }
		$result = $res_210;
		$this->pos = $pos_210;
		$_220 = FALSE; break;
	}
	while(0);
	if( $_220 === TRUE ) { return $this->finalise($result); }
	if( $_220 === FALSE) { return FALSE; }
}

public function EqualityComparableExpression_STR ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* Greater: '>' > second_operand:NumericExpression > */
protected $match_Greater_typestack = array('Greater');
function match_Greater ($stack = array()) {
	$matchrule = "Greater"; $result = $this->construct($matchrule, $matchrule, null);
	$_226 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
		}
		else { $_226 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_226 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_226 = TRUE; break;
	}
	while(0);
	if( $_226 === TRUE ) { return $this->finalise($result); }
	if( $_226 === FALSE) { return FALSE; }
}


/* Less: '<' > second_operand:NumericExpression > */
protected $match_Less_typestack = array('Less');
function match_Less ($stack = array()) {
	$matchrule = "Less"; $result = $this->construct($matchrule, $matchrule, null);
	$_232 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '<') {
			$this->pos += 1;
			$result["text"] .= '<';
		}
		else { $_232 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_232 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_232 = TRUE; break;
	}
	while(0);
	if( $_232 === TRUE ) { return $this->finalise($result); }
	if( $_232 === FALSE) { return FALSE; }
}


/* LessOrEqual: '<=' > second_operand:NumericExpression > */
protected $match_LessOrEqual_typestack = array('LessOrEqual');
function match_LessOrEqual ($stack = array()) {
	$matchrule = "LessOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_238 = NULL;
	do {
		if (( $subres = $this->literal( '<=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_238 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_238 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_238 = TRUE; break;
	}
	while(0);
	if( $_238 === TRUE ) { return $this->finalise($result); }
	if( $_238 === FALSE) { return FALSE; }
}


/* GreaterOrEqual: '>=' > second_operand:NumericExpression > */
protected $match_GreaterOrEqual_typestack = array('GreaterOrEqual');
function match_GreaterOrEqual ($stack = array()) {
	$matchrule = "GreaterOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_244 = NULL;
	do {
		if (( $subres = $this->literal( '>=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_244 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_244 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_244 = TRUE; break;
	}
	while(0);
	if( $_244 === TRUE ) { return $this->finalise($result); }
	if( $_244 === FALSE) { return FALSE; }
}


/* Equal: '==' > second_operand:EqualityComparableExpression > */
protected $match_Equal_typestack = array('Equal');
function match_Equal ($stack = array()) {
	$matchrule = "Equal"; $result = $this->construct($matchrule, $matchrule, null);
	$_250 = NULL;
	do {
		if (( $subres = $this->literal( '==' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_250 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_250 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_250 = TRUE; break;
	}
	while(0);
	if( $_250 === TRUE ) { return $this->finalise($result); }
	if( $_250 === FALSE) { return FALSE; }
}


/* NotEqual: '!=' > second_operand:EqualityComparableExpression > */
protected $match_NotEqual_typestack = array('NotEqual');
function match_NotEqual ($stack = array()) {
	$matchrule = "NotEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_256 = NULL;
	do {
		if (( $subres = $this->literal( '!=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_256 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_256 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_256 = TRUE; break;
	}
	while(0);
	if( $_256 === TRUE ) { return $this->finalise($result); }
	if( $_256 === FALSE) { return FALSE; }
}


/* And: '&&' > second_operand:BooleanValue > */
protected $match_And_typestack = array('And');
function match_And ($stack = array()) {
	$matchrule = "And"; $result = $this->construct($matchrule, $matchrule, null);
	$_262 = NULL;
	do {
		if (( $subres = $this->literal( '&&' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_262 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_262 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_262 = TRUE; break;
	}
	while(0);
	if( $_262 === TRUE ) { return $this->finalise($result); }
	if( $_262 === FALSE) { return FALSE; }
}


/* Or: '||' > second_operand:BooleanValue > */
protected $match_Or_typestack = array('Or');
function match_Or ($stack = array()) {
	$matchrule = "Or"; $result = $this->construct($matchrule, $matchrule, null);
	$_268 = NULL;
	do {
		if (( $subres = $this->literal( '||' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_268 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_268 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_268 = TRUE; break;
	}
	while(0);
	if( $_268 === TRUE ) { return $this->finalise($result); }
	if( $_268 === FALSE) { return FALSE; }
}


/* Not: '!' BooleanValue > */
protected $match_Not_typestack = array('Not');
function match_Not ($stack = array()) {
	$matchrule = "Not"; $result = $this->construct($matchrule, $matchrule, null);
	$_273 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '!') {
			$this->pos += 1;
			$result["text"] .= '!';
		}
		else { $_273 = FALSE; break; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_273 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_273 = TRUE; break;
	}
	while(0);
	if( $_273 === TRUE ) { return $this->finalise($result); }
	if( $_273 === FALSE) { return FALSE; }
}

public function Not_BooleanValue ( &$result, $sub ) {
		$result['expression'] = new BooleanUnaryExpression(BooleanUnaryExpression::TYPE_NOT, $sub['expression']);
	}

/* BooleanBinaryOperatorSign: '>' | '<' | '>=' | '<=' | '==' | '!=' | '&&' | '||' */
protected $match_BooleanBinaryOperatorSign_typestack = array('BooleanBinaryOperatorSign');
function match_BooleanBinaryOperatorSign ($stack = array()) {
	$matchrule = "BooleanBinaryOperatorSign"; $result = $this->construct($matchrule, $matchrule, null);
	$_302 = NULL;
	do {
		$res_275 = $result;
		$pos_275 = $this->pos;
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
			$_302 = TRUE; break;
		}
		$result = $res_275;
		$this->pos = $pos_275;
		$_300 = NULL;
		do {
			$res_277 = $result;
			$pos_277 = $this->pos;
			if (substr($this->string,$this->pos,1) == '<') {
				$this->pos += 1;
				$result["text"] .= '<';
				$_300 = TRUE; break;
			}
			$result = $res_277;
			$this->pos = $pos_277;
			$_298 = NULL;
			do {
				$res_279 = $result;
				$pos_279 = $this->pos;
				if (( $subres = $this->literal( '>=' ) ) !== FALSE) {
					$result["text"] .= $subres;
					$_298 = TRUE; break;
				}
				$result = $res_279;
				$this->pos = $pos_279;
				$_296 = NULL;
				do {
					$res_281 = $result;
					$pos_281 = $this->pos;
					if (( $subres = $this->literal( '<=' ) ) !== FALSE) {
						$result["text"] .= $subres;
						$_296 = TRUE; break;
					}
					$result = $res_281;
					$this->pos = $pos_281;
					$_294 = NULL;
					do {
						$res_283 = $result;
						$pos_283 = $this->pos;
						if (( $subres = $this->literal( '==' ) ) !== FALSE) {
							$result["text"] .= $subres;
							$_294 = TRUE; break;
						}
						$result = $res_283;
						$this->pos = $pos_283;
						$_292 = NULL;
						do {
							$res_285 = $result;
							$pos_285 = $this->pos;
							if (( $subres = $this->literal( '!=' ) ) !== FALSE) {
								$result["text"] .= $subres;
								$_292 = TRUE; break;
							}
							$result = $res_285;
							$this->pos = $pos_285;
							$_290 = NULL;
							do {
								$res_287 = $result;
								$pos_287 = $this->pos;
								if (( $subres = $this->literal( '&&' ) ) !== FALSE) {
									$result["text"] .= $subres;
									$_290 = TRUE; break;
								}
								$result = $res_287;
								$this->pos = $pos_287;
								if (( $subres = $this->literal( '||' ) ) !== FALSE) {
									$result["text"] .= $subres;
									$_290 = TRUE; break;
								}
								$result = $res_287;
								$this->pos = $pos_287;
								$_290 = FALSE; break;
							}
							while(0);
							if( $_290 === TRUE ) { $_292 = TRUE; break; }
							$result = $res_285;
							$this->pos = $pos_285;
							$_292 = FALSE; break;
						}
						while(0);
						if( $_292 === TRUE ) { $_294 = TRUE; break; }
						$result = $res_283;
						$this->pos = $pos_283;
						$_294 = FALSE; break;
					}
					while(0);
					if( $_294 === TRUE ) { $_296 = TRUE; break; }
					$result = $res_281;
					$this->pos = $pos_281;
					$_296 = FALSE; break;
				}
				while(0);
				if( $_296 === TRUE ) { $_298 = TRUE; break; }
				$result = $res_279;
				$this->pos = $pos_279;
				$_298 = FALSE; break;
			}
			while(0);
			if( $_298 === TRUE ) { $_300 = TRUE; break; }
			$result = $res_277;
			$this->pos = $pos_277;
			$_300 = FALSE; break;
		}
		while(0);
		if( $_300 === TRUE ) { $_302 = TRUE; break; }
		$result = $res_275;
		$this->pos = $pos_275;
		$_302 = FALSE; break;
	}
	while(0);
	if( $_302 === TRUE ) { return $this->finalise($result); }
	if( $_302 === FALSE) { return FALSE; }
}


/* BooleanOperation: Not | NumericExpression > ( Greater | Less | LessOrEqual | GreaterOrEqual ) | EqualityComparableExpression > ( Equal | NotEqual ) | BooleanValue > ( And | Or ) > */
protected $match_BooleanOperation_typestack = array('BooleanOperation');
function match_BooleanOperation ($stack = array()) {
	$matchrule = "BooleanOperation"; $result = $this->construct($matchrule, $matchrule, null);
	$_354 = NULL;
	do {
		$res_304 = $result;
		$pos_304 = $this->pos;
		$matcher = 'match_'.'Not'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_354 = TRUE; break;
		}
		$result = $res_304;
		$this->pos = $pos_304;
		$_352 = NULL;
		do {
			$res_306 = $result;
			$pos_306 = $this->pos;
			$_324 = NULL;
			do {
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_324 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_322 = NULL;
				do {
					$_320 = NULL;
					do {
						$res_309 = $result;
						$pos_309 = $this->pos;
						$matcher = 'match_'.'Greater'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_320 = TRUE; break;
						}
						$result = $res_309;
						$this->pos = $pos_309;
						$_318 = NULL;
						do {
							$res_311 = $result;
							$pos_311 = $this->pos;
							$matcher = 'match_'.'Less'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_318 = TRUE; break;
							}
							$result = $res_311;
							$this->pos = $pos_311;
							$_316 = NULL;
							do {
								$res_313 = $result;
								$pos_313 = $this->pos;
								$matcher = 'match_'.'LessOrEqual'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_316 = TRUE; break;
								}
								$result = $res_313;
								$this->pos = $pos_313;
								$matcher = 'match_'.'GreaterOrEqual'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_316 = TRUE; break;
								}
								$result = $res_313;
								$this->pos = $pos_313;
								$_316 = FALSE; break;
							}
							while(0);
							if( $_316 === TRUE ) { $_318 = TRUE; break; }
							$result = $res_311;
							$this->pos = $pos_311;
							$_318 = FALSE; break;
						}
						while(0);
						if( $_318 === TRUE ) { $_320 = TRUE; break; }
						$result = $res_309;
						$this->pos = $pos_309;
						$_320 = FALSE; break;
					}
					while(0);
					if( $_320 === FALSE) { $_322 = FALSE; break; }
					$_322 = TRUE; break;
				}
				while(0);
				if( $_322 === FALSE) { $_324 = FALSE; break; }
				$_324 = TRUE; break;
			}
			while(0);
			if( $_324 === TRUE ) { $_352 = TRUE; break; }
			$result = $res_306;
			$this->pos = $pos_306;
			$_350 = NULL;
			do {
				$res_326 = $result;
				$pos_326 = $this->pos;
				$_336 = NULL;
				do {
					$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_336 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_334 = NULL;
					do {
						$_332 = NULL;
						do {
							$res_329 = $result;
							$pos_329 = $this->pos;
							$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_332 = TRUE; break;
							}
							$result = $res_329;
							$this->pos = $pos_329;
							$matcher = 'match_'.'NotEqual'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_332 = TRUE; break;
							}
							$result = $res_329;
							$this->pos = $pos_329;
							$_332 = FALSE; break;
						}
						while(0);
						if( $_332 === FALSE) { $_334 = FALSE; break; }
						$_334 = TRUE; break;
					}
					while(0);
					if( $_334 === FALSE) { $_336 = FALSE; break; }
					$_336 = TRUE; break;
				}
				while(0);
				if( $_336 === TRUE ) { $_350 = TRUE; break; }
				$result = $res_326;
				$this->pos = $pos_326;
				$_348 = NULL;
				do {
					$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_348 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_345 = NULL;
					do {
						$_343 = NULL;
						do {
							$res_340 = $result;
							$pos_340 = $this->pos;
							$matcher = 'match_'.'And'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_343 = TRUE; break;
							}
							$result = $res_340;
							$this->pos = $pos_340;
							$matcher = 'match_'.'Or'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_343 = TRUE; break;
							}
							$result = $res_340;
							$this->pos = $pos_340;
							$_343 = FALSE; break;
						}
						while(0);
						if( $_343 === FALSE) { $_345 = FALSE; break; }
						$_345 = TRUE; break;
					}
					while(0);
					if( $_345 === FALSE) { $_348 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_348 = TRUE; break;
				}
				while(0);
				if( $_348 === TRUE ) { $_350 = TRUE; break; }
				$result = $res_326;
				$this->pos = $pos_326;
				$_350 = FALSE; break;
			}
			while(0);
			if( $_350 === TRUE ) { $_352 = TRUE; break; }
			$result = $res_306;
			$this->pos = $pos_306;
			$_352 = FALSE; break;
		}
		while(0);
		if( $_352 === TRUE ) { $_354 = TRUE; break; }
		$result = $res_304;
		$this->pos = $pos_304;
		$_354 = FALSE; break;
	}
	while(0);
	if( $_354 === TRUE ) { return $this->finalise($result); }
	if( $_354 === FALSE) { return FALSE; }
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
	$_362 = NULL;
	do {
		$res_356 = $result;
		$pos_356 = $this->pos;
		$matcher = 'match_'.'BooleanOperation'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_362 = TRUE; break;
		}
		$result = $res_356;
		$this->pos = $pos_356;
		$_360 = NULL;
		do {
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_360 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_360 = TRUE; break;
		}
		while(0);
		if( $_360 === TRUE ) { $_362 = TRUE; break; }
		$result = $res_356;
		$this->pos = $pos_356;
		$_362 = FALSE; break;
	}
	while(0);
	if( $_362 === TRUE ) { return $this->finalise($result); }
	if( $_362 === FALSE) { return FALSE; }
}

public function BooleanExpression_STR ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* NumericValue: Number > | Function > | Variable > | '(' > NumericExpression > ')' > */
protected $match_NumericValue_typestack = array('NumericValue');
function match_NumericValue ($stack = array()) {
	$matchrule = "NumericValue"; $result = $this->construct($matchrule, $matchrule, null);
	$_391 = NULL;
	do {
		$res_364 = $result;
		$pos_364 = $this->pos;
		$_367 = NULL;
		do {
			$matcher = 'match_'.'Number'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_367 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_367 = TRUE; break;
		}
		while(0);
		if( $_367 === TRUE ) { $_391 = TRUE; break; }
		$result = $res_364;
		$this->pos = $pos_364;
		$_389 = NULL;
		do {
			$res_369 = $result;
			$pos_369 = $this->pos;
			$_372 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_372 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_372 = TRUE; break;
			}
			while(0);
			if( $_372 === TRUE ) { $_389 = TRUE; break; }
			$result = $res_369;
			$this->pos = $pos_369;
			$_387 = NULL;
			do {
				$res_374 = $result;
				$pos_374 = $this->pos;
				$_377 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_377 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_377 = TRUE; break;
				}
				while(0);
				if( $_377 === TRUE ) { $_387 = TRUE; break; }
				$result = $res_374;
				$this->pos = $pos_374;
				$_385 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_385 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_385 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_385 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_385 = TRUE; break;
				}
				while(0);
				if( $_385 === TRUE ) { $_387 = TRUE; break; }
				$result = $res_374;
				$this->pos = $pos_374;
				$_387 = FALSE; break;
			}
			while(0);
			if( $_387 === TRUE ) { $_389 = TRUE; break; }
			$result = $res_369;
			$this->pos = $pos_369;
			$_389 = FALSE; break;
		}
		while(0);
		if( $_389 === TRUE ) { $_391 = TRUE; break; }
		$result = $res_364;
		$this->pos = $pos_364;
		$_391 = FALSE; break;
	}
	while(0);
	if( $_391 === TRUE ) { return $this->finalise($result); }
	if( $_391 === FALSE) { return FALSE; }
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
	$_397 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '*') {
			$this->pos += 1;
			$result["text"] .= '*';
		}
		else { $_397 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_397 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_397 = TRUE; break;
	}
	while(0);
	if( $_397 === TRUE ) { return $this->finalise($result); }
	if( $_397 === FALSE) { return FALSE; }
}


/* Div: '/' > second_operand:NumericValue > */
protected $match_Div_typestack = array('Div');
function match_Div ($stack = array()) {
	$matchrule = "Div"; $result = $this->construct($matchrule, $matchrule, null);
	$_403 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '/') {
			$this->pos += 1;
			$result["text"] .= '/';
		}
		else { $_403 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_403 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_403 = TRUE; break;
	}
	while(0);
	if( $_403 === TRUE ) { return $this->finalise($result); }
	if( $_403 === FALSE) { return FALSE; }
}


/* Mod: '%' > second_operand:NumericValue > */
protected $match_Mod_typestack = array('Mod');
function match_Mod ($stack = array()) {
	$matchrule = "Mod"; $result = $this->construct($matchrule, $matchrule, null);
	$_409 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '%') {
			$this->pos += 1;
			$result["text"] .= '%';
		}
		else { $_409 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_409 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_409 = TRUE; break;
	}
	while(0);
	if( $_409 === TRUE ) { return $this->finalise($result); }
	if( $_409 === FALSE) { return FALSE; }
}


/* Product: NumericValue > ( Mul | Div | Mod )* */
protected $match_Product_typestack = array('Product');
function match_Product ($stack = array()) {
	$matchrule = "Product"; $result = $this->construct($matchrule, $matchrule, null);
	$_424 = NULL;
	do {
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_424 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_423 = $result;
			$pos_423 = $this->pos;
			$_422 = NULL;
			do {
				$_420 = NULL;
				do {
					$res_413 = $result;
					$pos_413 = $this->pos;
					$matcher = 'match_'.'Mul'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_420 = TRUE; break;
					}
					$result = $res_413;
					$this->pos = $pos_413;
					$_418 = NULL;
					do {
						$res_415 = $result;
						$pos_415 = $this->pos;
						$matcher = 'match_'.'Div'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_418 = TRUE; break;
						}
						$result = $res_415;
						$this->pos = $pos_415;
						$matcher = 'match_'.'Mod'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_418 = TRUE; break;
						}
						$result = $res_415;
						$this->pos = $pos_415;
						$_418 = FALSE; break;
					}
					while(0);
					if( $_418 === TRUE ) { $_420 = TRUE; break; }
					$result = $res_413;
					$this->pos = $pos_413;
					$_420 = FALSE; break;
				}
				while(0);
				if( $_420 === FALSE) { $_422 = FALSE; break; }
				$_422 = TRUE; break;
			}
			while(0);
			if( $_422 === FALSE) {
				$result = $res_423;
				$this->pos = $pos_423;
				unset( $res_423 );
				unset( $pos_423 );
				break;
			}
		}
		$_424 = TRUE; break;
	}
	while(0);
	if( $_424 === TRUE ) { return $this->finalise($result); }
	if( $_424 === FALSE) { return FALSE; }
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
	$_428 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_428 = FALSE; break; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_428 = FALSE; break; }
		$_428 = TRUE; break;
	}
	while(0);
	if( $_428 === TRUE ) { return $this->finalise($result); }
	if( $_428 === FALSE) { return FALSE; }
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
	$_434 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '+') {
			$this->pos += 1;
			$result["text"] .= '+';
		}
		else { $_434 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_434 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_434 = TRUE; break;
	}
	while(0);
	if( $_434 === TRUE ) { return $this->finalise($result); }
	if( $_434 === FALSE) { return FALSE; }
}


/* Minus: '-' > second_operand:Product > */
protected $match_Minus_typestack = array('Minus');
function match_Minus ($stack = array()) {
	$matchrule = "Minus"; $result = $this->construct($matchrule, $matchrule, null);
	$_440 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_440 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_440 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_440 = TRUE; break;
	}
	while(0);
	if( $_440 === TRUE ) { return $this->finalise($result); }
	if( $_440 === FALSE) { return FALSE; }
}


/* Sum: ( MinusProduct | Product ) > ( Plus | Minus )* */
protected $match_Sum_typestack = array('Sum');
function match_Sum ($stack = array()) {
	$matchrule = "Sum"; $result = $this->construct($matchrule, $matchrule, null);
	$_457 = NULL;
	do {
		$_447 = NULL;
		do {
			$_445 = NULL;
			do {
				$res_442 = $result;
				$pos_442 = $this->pos;
				$matcher = 'match_'.'MinusProduct'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_445 = TRUE; break;
				}
				$result = $res_442;
				$this->pos = $pos_442;
				$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_445 = TRUE; break;
				}
				$result = $res_442;
				$this->pos = $pos_442;
				$_445 = FALSE; break;
			}
			while(0);
			if( $_445 === FALSE) { $_447 = FALSE; break; }
			$_447 = TRUE; break;
		}
		while(0);
		if( $_447 === FALSE) { $_457 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_456 = $result;
			$pos_456 = $this->pos;
			$_455 = NULL;
			do {
				$_453 = NULL;
				do {
					$res_450 = $result;
					$pos_450 = $this->pos;
					$matcher = 'match_'.'Plus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_453 = TRUE; break;
					}
					$result = $res_450;
					$this->pos = $pos_450;
					$matcher = 'match_'.'Minus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_453 = TRUE; break;
					}
					$result = $res_450;
					$this->pos = $pos_450;
					$_453 = FALSE; break;
				}
				while(0);
				if( $_453 === FALSE) { $_455 = FALSE; break; }
				$_455 = TRUE; break;
			}
			while(0);
			if( $_455 === FALSE) {
				$result = $res_456;
				$this->pos = $pos_456;
				unset( $res_456 );
				unset( $pos_456 );
				break;
			}
		}
		$_457 = TRUE; break;
	}
	while(0);
	if( $_457 === TRUE ) { return $this->finalise($result); }
	if( $_457 === FALSE) { return FALSE; }
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
	$_461 = NULL;
	do {
		$matcher = 'match_'.'Sum'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_461 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_461 = TRUE; break;
	}
	while(0);
	if( $_461 === TRUE ) { return $this->finalise($result); }
	if( $_461 === FALSE) { return FALSE; }
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
	$_477 = NULL;
	do {
		$res_464 = $result;
		$pos_464 = $this->pos;
		$_467 = NULL;
		do {
			$matcher = 'match_'.'StringExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_467 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_467 = TRUE; break;
		}
		while(0);
		if( $_467 === TRUE ) { $_477 = TRUE; break; }
		$result = $res_464;
		$this->pos = $pos_464;
		$_475 = NULL;
		do {
			$res_469 = $result;
			$pos_469 = $this->pos;
			$_472 = NULL;
			do {
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_472 = FALSE; break; }
				$res_471 = $result;
				$pos_471 = $this->pos;
				$matcher = 'match_'.'BooleanBinaryOperatorSign'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$result = $res_471;
					$this->pos = $pos_471;
					$_472 = FALSE; break;
				}
				else {
					$result = $res_471;
					$this->pos = $pos_471;
				}
				$_472 = TRUE; break;
			}
			while(0);
			if( $_472 === TRUE ) { $_475 = TRUE; break; }
			$result = $res_469;
			$this->pos = $pos_469;
			$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_475 = TRUE; break;
			}
			$result = $res_469;
			$this->pos = $pos_469;
			$_475 = FALSE; break;
		}
		while(0);
		if( $_475 === TRUE ) { $_477 = TRUE; break; }
		$result = $res_464;
		$this->pos = $pos_464;
		$_477 = FALSE; break;
	}
	while(0);
	if( $_477 === TRUE ) { return $this->finalise($result); }
	if( $_477 === FALSE) { return FALSE; }
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
	$_488 = NULL;
	do {
		$res_479 = $result;
		$pos_479 = $this->pos;
		$_482 = NULL;
		do {
			$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_482 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_482 = TRUE; break;
		}
		while(0);
		if( $_482 === TRUE ) { $_488 = TRUE; break; }
		$result = $res_479;
		$this->pos = $pos_479;
		$_486 = NULL;
		do {
			$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_486 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_486 = TRUE; break;
		}
		while(0);
		if( $_486 === TRUE ) { $_488 = TRUE; break; }
		$result = $res_479;
		$this->pos = $pos_479;
		$_488 = FALSE; break;
	}
	while(0);
	if( $_488 === TRUE ) { return $this->finalise($result); }
	if( $_488 === FALSE) { return FALSE; }
}

public function ArrayExpression_Function ( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}

public function ArrayExpression_Variable ( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}



}