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

/* Validation: Expression ( > '@' > message:StringExpression > ( '%' > tag:StringExpression )? )? > */
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
			$matcher = 'match_'.'StringExpression'; $key = $matcher; $pos = $this->pos;
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
				$matcher = 'match_'.'StringExpression'; $key = $matcher; $pos = $this->pos;
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
		$result['validation']->setMessageExpression($sub['expression']);
	}

public function Validation_tag ( &$result, $sub ) {
		$result['validation']->setTagExpression($sub['expression']);
	}

/* BooleanValueNarrow: Boolean > | Function > | Variable > | '(' > BooleanExpression > ')' > */
protected $match_BooleanValueNarrow_typestack = array('BooleanValueNarrow');
function match_BooleanValueNarrow ($stack = array()) {
	$matchrule = "BooleanValueNarrow"; $result = $this->construct($matchrule, $matchrule, null);
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

public function BooleanValueNarrow_Boolean ( &$result, $sub ) {
		$result['expression'] = new BooleanConstExpression($sub['text']);
	}

public function BooleanValueNarrow_Function ( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}

public function BooleanValueNarrow_Variable ( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}

public function BooleanValueNarrow_BooleanExpression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

public function BooleanValueNarrow_CompareExpression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* BooleanValue: CompareExpression > | BooleanValueNarrow > */
protected $match_BooleanValue_typestack = array('BooleanValue');
function match_BooleanValue ($stack = array()) {
	$matchrule = "BooleanValue"; $result = $this->construct($matchrule, $matchrule, null);
	$_219 = NULL;
	do {
		$res_210 = $result;
		$pos_210 = $this->pos;
		$_213 = NULL;
		do {
			$matcher = 'match_'.'CompareExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_213 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_213 = TRUE; break;
		}
		while(0);
		if( $_213 === TRUE ) { $_219 = TRUE; break; }
		$result = $res_210;
		$this->pos = $pos_210;
		$_217 = NULL;
		do {
			$matcher = 'match_'.'BooleanValueNarrow'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_217 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_217 = TRUE; break;
		}
		while(0);
		if( $_217 === TRUE ) { $_219 = TRUE; break; }
		$result = $res_210;
		$this->pos = $pos_210;
		$_219 = FALSE; break;
	}
	while(0);
	if( $_219 === TRUE ) { return $this->finalise($result); }
	if( $_219 === FALSE) { return FALSE; }
}

public function BooleanValue_STR ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* EqualityComparableExpression: NumericExpression | BooleanValueNarrow | StringExpression > */
protected $match_EqualityComparableExpression_typestack = array('EqualityComparableExpression');
function match_EqualityComparableExpression ($stack = array()) {
	$matchrule = "EqualityComparableExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_231 = NULL;
	do {
		$res_221 = $result;
		$pos_221 = $this->pos;
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_231 = TRUE; break;
		}
		$result = $res_221;
		$this->pos = $pos_221;
		$_229 = NULL;
		do {
			$res_223 = $result;
			$pos_223 = $this->pos;
			$matcher = 'match_'.'BooleanValueNarrow'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_229 = TRUE; break;
			}
			$result = $res_223;
			$this->pos = $pos_223;
			$_227 = NULL;
			do {
				$matcher = 'match_'.'StringExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_227 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_227 = TRUE; break;
			}
			while(0);
			if( $_227 === TRUE ) { $_229 = TRUE; break; }
			$result = $res_223;
			$this->pos = $pos_223;
			$_229 = FALSE; break;
		}
		while(0);
		if( $_229 === TRUE ) { $_231 = TRUE; break; }
		$result = $res_221;
		$this->pos = $pos_221;
		$_231 = FALSE; break;
	}
	while(0);
	if( $_231 === TRUE ) { return $this->finalise($result); }
	if( $_231 === FALSE) { return FALSE; }
}

public function EqualityComparableExpression_STR ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* Greater: '>' > second_operand:NumericExpression > */
protected $match_Greater_typestack = array('Greater');
function match_Greater ($stack = array()) {
	$matchrule = "Greater"; $result = $this->construct($matchrule, $matchrule, null);
	$_237 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
		}
		else { $_237 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_237 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_237 = TRUE; break;
	}
	while(0);
	if( $_237 === TRUE ) { return $this->finalise($result); }
	if( $_237 === FALSE) { return FALSE; }
}


/* Less: '<' > second_operand:NumericExpression > */
protected $match_Less_typestack = array('Less');
function match_Less ($stack = array()) {
	$matchrule = "Less"; $result = $this->construct($matchrule, $matchrule, null);
	$_243 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '<') {
			$this->pos += 1;
			$result["text"] .= '<';
		}
		else { $_243 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_243 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_243 = TRUE; break;
	}
	while(0);
	if( $_243 === TRUE ) { return $this->finalise($result); }
	if( $_243 === FALSE) { return FALSE; }
}


/* LessOrEqual: '<=' > second_operand:NumericExpression > */
protected $match_LessOrEqual_typestack = array('LessOrEqual');
function match_LessOrEqual ($stack = array()) {
	$matchrule = "LessOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_249 = NULL;
	do {
		if (( $subres = $this->literal( '<=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_249 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_249 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_249 = TRUE; break;
	}
	while(0);
	if( $_249 === TRUE ) { return $this->finalise($result); }
	if( $_249 === FALSE) { return FALSE; }
}


/* GreaterOrEqual: '>=' > second_operand:NumericExpression > */
protected $match_GreaterOrEqual_typestack = array('GreaterOrEqual');
function match_GreaterOrEqual ($stack = array()) {
	$matchrule = "GreaterOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_255 = NULL;
	do {
		if (( $subres = $this->literal( '>=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_255 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_255 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_255 = TRUE; break;
	}
	while(0);
	if( $_255 === TRUE ) { return $this->finalise($result); }
	if( $_255 === FALSE) { return FALSE; }
}


/* Equal: '==' > second_operand:EqualityComparableExpression > */
protected $match_Equal_typestack = array('Equal');
function match_Equal ($stack = array()) {
	$matchrule = "Equal"; $result = $this->construct($matchrule, $matchrule, null);
	$_261 = NULL;
	do {
		if (( $subres = $this->literal( '==' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_261 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_261 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_261 = TRUE; break;
	}
	while(0);
	if( $_261 === TRUE ) { return $this->finalise($result); }
	if( $_261 === FALSE) { return FALSE; }
}


/* NotEqual: '!=' > second_operand:EqualityComparableExpression > */
protected $match_NotEqual_typestack = array('NotEqual');
function match_NotEqual ($stack = array()) {
	$matchrule = "NotEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_267 = NULL;
	do {
		if (( $subres = $this->literal( '!=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_267 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_267 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_267 = TRUE; break;
	}
	while(0);
	if( $_267 === TRUE ) { return $this->finalise($result); }
	if( $_267 === FALSE) { return FALSE; }
}


/* CompareExpression: NumericExpression > ( Greater | Less | LessOrEqual | GreaterOrEqual ) | EqualityComparableExpression > ( Equal | NotEqual ) */
protected $match_CompareExpression_typestack = array('CompareExpression');
function match_CompareExpression ($stack = array()) {
	$matchrule = "CompareExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_300 = NULL;
	do {
		$res_269 = $result;
		$pos_269 = $this->pos;
		$_287 = NULL;
		do {
			$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_287 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_285 = NULL;
			do {
				$_283 = NULL;
				do {
					$res_272 = $result;
					$pos_272 = $this->pos;
					$matcher = 'match_'.'Greater'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_283 = TRUE; break;
					}
					$result = $res_272;
					$this->pos = $pos_272;
					$_281 = NULL;
					do {
						$res_274 = $result;
						$pos_274 = $this->pos;
						$matcher = 'match_'.'Less'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_281 = TRUE; break;
						}
						$result = $res_274;
						$this->pos = $pos_274;
						$_279 = NULL;
						do {
							$res_276 = $result;
							$pos_276 = $this->pos;
							$matcher = 'match_'.'LessOrEqual'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_279 = TRUE; break;
							}
							$result = $res_276;
							$this->pos = $pos_276;
							$matcher = 'match_'.'GreaterOrEqual'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_279 = TRUE; break;
							}
							$result = $res_276;
							$this->pos = $pos_276;
							$_279 = FALSE; break;
						}
						while(0);
						if( $_279 === TRUE ) { $_281 = TRUE; break; }
						$result = $res_274;
						$this->pos = $pos_274;
						$_281 = FALSE; break;
					}
					while(0);
					if( $_281 === TRUE ) { $_283 = TRUE; break; }
					$result = $res_272;
					$this->pos = $pos_272;
					$_283 = FALSE; break;
				}
				while(0);
				if( $_283 === FALSE) { $_285 = FALSE; break; }
				$_285 = TRUE; break;
			}
			while(0);
			if( $_285 === FALSE) { $_287 = FALSE; break; }
			$_287 = TRUE; break;
		}
		while(0);
		if( $_287 === TRUE ) { $_300 = TRUE; break; }
		$result = $res_269;
		$this->pos = $pos_269;
		$_298 = NULL;
		do {
			$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_298 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_296 = NULL;
			do {
				$_294 = NULL;
				do {
					$res_291 = $result;
					$pos_291 = $this->pos;
					$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_294 = TRUE; break;
					}
					$result = $res_291;
					$this->pos = $pos_291;
					$matcher = 'match_'.'NotEqual'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_294 = TRUE; break;
					}
					$result = $res_291;
					$this->pos = $pos_291;
					$_294 = FALSE; break;
				}
				while(0);
				if( $_294 === FALSE) { $_296 = FALSE; break; }
				$_296 = TRUE; break;
			}
			while(0);
			if( $_296 === FALSE) { $_298 = FALSE; break; }
			$_298 = TRUE; break;
		}
		while(0);
		if( $_298 === TRUE ) { $_300 = TRUE; break; }
		$result = $res_269;
		$this->pos = $pos_269;
		$_300 = FALSE; break;
	}
	while(0);
	if( $_300 === TRUE ) { return $this->finalise($result); }
	if( $_300 === FALSE) { return FALSE; }
}

public function CompareExpression_NumericExpression ( &$result, $sub ) {
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

public function CompareExpression_EqualityComparableExpression ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
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

/* BooleanBinaryOperatorSign: '>' | '<' | '>=' | '<=' | '==' | '!=' | '&&' | '||' */
protected $match_BooleanBinaryOperatorSign_typestack = array('BooleanBinaryOperatorSign');
function match_BooleanBinaryOperatorSign ($stack = array()) {
	$matchrule = "BooleanBinaryOperatorSign"; $result = $this->construct($matchrule, $matchrule, null);
	$_329 = NULL;
	do {
		$res_302 = $result;
		$pos_302 = $this->pos;
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
			$_329 = TRUE; break;
		}
		$result = $res_302;
		$this->pos = $pos_302;
		$_327 = NULL;
		do {
			$res_304 = $result;
			$pos_304 = $this->pos;
			if (substr($this->string,$this->pos,1) == '<') {
				$this->pos += 1;
				$result["text"] .= '<';
				$_327 = TRUE; break;
			}
			$result = $res_304;
			$this->pos = $pos_304;
			$_325 = NULL;
			do {
				$res_306 = $result;
				$pos_306 = $this->pos;
				if (( $subres = $this->literal( '>=' ) ) !== FALSE) {
					$result["text"] .= $subres;
					$_325 = TRUE; break;
				}
				$result = $res_306;
				$this->pos = $pos_306;
				$_323 = NULL;
				do {
					$res_308 = $result;
					$pos_308 = $this->pos;
					if (( $subres = $this->literal( '<=' ) ) !== FALSE) {
						$result["text"] .= $subres;
						$_323 = TRUE; break;
					}
					$result = $res_308;
					$this->pos = $pos_308;
					$_321 = NULL;
					do {
						$res_310 = $result;
						$pos_310 = $this->pos;
						if (( $subres = $this->literal( '==' ) ) !== FALSE) {
							$result["text"] .= $subres;
							$_321 = TRUE; break;
						}
						$result = $res_310;
						$this->pos = $pos_310;
						$_319 = NULL;
						do {
							$res_312 = $result;
							$pos_312 = $this->pos;
							if (( $subres = $this->literal( '!=' ) ) !== FALSE) {
								$result["text"] .= $subres;
								$_319 = TRUE; break;
							}
							$result = $res_312;
							$this->pos = $pos_312;
							$_317 = NULL;
							do {
								$res_314 = $result;
								$pos_314 = $this->pos;
								if (( $subres = $this->literal( '&&' ) ) !== FALSE) {
									$result["text"] .= $subres;
									$_317 = TRUE; break;
								}
								$result = $res_314;
								$this->pos = $pos_314;
								if (( $subres = $this->literal( '||' ) ) !== FALSE) {
									$result["text"] .= $subres;
									$_317 = TRUE; break;
								}
								$result = $res_314;
								$this->pos = $pos_314;
								$_317 = FALSE; break;
							}
							while(0);
							if( $_317 === TRUE ) { $_319 = TRUE; break; }
							$result = $res_312;
							$this->pos = $pos_312;
							$_319 = FALSE; break;
						}
						while(0);
						if( $_319 === TRUE ) { $_321 = TRUE; break; }
						$result = $res_310;
						$this->pos = $pos_310;
						$_321 = FALSE; break;
					}
					while(0);
					if( $_321 === TRUE ) { $_323 = TRUE; break; }
					$result = $res_308;
					$this->pos = $pos_308;
					$_323 = FALSE; break;
				}
				while(0);
				if( $_323 === TRUE ) { $_325 = TRUE; break; }
				$result = $res_306;
				$this->pos = $pos_306;
				$_325 = FALSE; break;
			}
			while(0);
			if( $_325 === TRUE ) { $_327 = TRUE; break; }
			$result = $res_304;
			$this->pos = $pos_304;
			$_327 = FALSE; break;
		}
		while(0);
		if( $_327 === TRUE ) { $_329 = TRUE; break; }
		$result = $res_302;
		$this->pos = $pos_302;
		$_329 = FALSE; break;
	}
	while(0);
	if( $_329 === TRUE ) { return $this->finalise($result); }
	if( $_329 === FALSE) { return FALSE; }
}


/* And: '&&' > second_operand:BooleanValue > */
protected $match_And_typestack = array('And');
function match_And ($stack = array()) {
	$matchrule = "And"; $result = $this->construct($matchrule, $matchrule, null);
	$_335 = NULL;
	do {
		if (( $subres = $this->literal( '&&' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_335 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_335 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_335 = TRUE; break;
	}
	while(0);
	if( $_335 === TRUE ) { return $this->finalise($result); }
	if( $_335 === FALSE) { return FALSE; }
}


/* Or: '||' > second_operand:BooleanValue > */
protected $match_Or_typestack = array('Or');
function match_Or ($stack = array()) {
	$matchrule = "Or"; $result = $this->construct($matchrule, $matchrule, null);
	$_341 = NULL;
	do {
		if (( $subres = $this->literal( '||' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_341 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_341 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_341 = TRUE; break;
	}
	while(0);
	if( $_341 === TRUE ) { return $this->finalise($result); }
	if( $_341 === FALSE) { return FALSE; }
}


/* Not: '!' BooleanValue > */
protected $match_Not_typestack = array('Not');
function match_Not ($stack = array()) {
	$matchrule = "Not"; $result = $this->construct($matchrule, $matchrule, null);
	$_346 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '!') {
			$this->pos += 1;
			$result["text"] .= '!';
		}
		else { $_346 = FALSE; break; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_346 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_346 = TRUE; break;
	}
	while(0);
	if( $_346 === TRUE ) { return $this->finalise($result); }
	if( $_346 === FALSE) { return FALSE; }
}

public function Not_BooleanValue ( &$result, $sub ) {
		$result['expression'] = new BooleanUnaryExpression(BooleanUnaryExpression::TYPE_NOT, $sub['expression']);
	}

/* BooleanOperation: Not | BooleanValue > ( And | Or ) > */
protected $match_BooleanOperation_typestack = array('BooleanOperation');
function match_BooleanOperation ($stack = array()) {
	$matchrule = "BooleanOperation"; $result = $this->construct($matchrule, $matchrule, null);
	$_362 = NULL;
	do {
		$res_348 = $result;
		$pos_348 = $this->pos;
		$matcher = 'match_'.'Not'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_362 = TRUE; break;
		}
		$result = $res_348;
		$this->pos = $pos_348;
		$_360 = NULL;
		do {
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_360 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_357 = NULL;
			do {
				$_355 = NULL;
				do {
					$res_352 = $result;
					$pos_352 = $this->pos;
					$matcher = 'match_'.'And'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_355 = TRUE; break;
					}
					$result = $res_352;
					$this->pos = $pos_352;
					$matcher = 'match_'.'Or'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_355 = TRUE; break;
					}
					$result = $res_352;
					$this->pos = $pos_352;
					$_355 = FALSE; break;
				}
				while(0);
				if( $_355 === FALSE) { $_357 = FALSE; break; }
				$_357 = TRUE; break;
			}
			while(0);
			if( $_357 === FALSE) { $_360 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_360 = TRUE; break;
		}
		while(0);
		if( $_360 === TRUE ) { $_362 = TRUE; break; }
		$result = $res_348;
		$this->pos = $pos_348;
		$_362 = FALSE; break;
	}
	while(0);
	if( $_362 === TRUE ) { return $this->finalise($result); }
	if( $_362 === FALSE) { return FALSE; }
}

public function BooleanOperation_Not ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
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
	$_370 = NULL;
	do {
		$res_364 = $result;
		$pos_364 = $this->pos;
		$matcher = 'match_'.'BooleanOperation'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_370 = TRUE; break;
		}
		$result = $res_364;
		$this->pos = $pos_364;
		$_368 = NULL;
		do {
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_368 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_368 = TRUE; break;
		}
		while(0);
		if( $_368 === TRUE ) { $_370 = TRUE; break; }
		$result = $res_364;
		$this->pos = $pos_364;
		$_370 = FALSE; break;
	}
	while(0);
	if( $_370 === TRUE ) { return $this->finalise($result); }
	if( $_370 === FALSE) { return FALSE; }
}

public function BooleanExpression_STR ( &$result, $sub ) {
		$result['expression'] = $sub['expression'];
	}

/* NumericValue: Number > | Function > | Variable > | '(' > NumericExpression > ')' > */
protected $match_NumericValue_typestack = array('NumericValue');
function match_NumericValue ($stack = array()) {
	$matchrule = "NumericValue"; $result = $this->construct($matchrule, $matchrule, null);
	$_399 = NULL;
	do {
		$res_372 = $result;
		$pos_372 = $this->pos;
		$_375 = NULL;
		do {
			$matcher = 'match_'.'Number'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_375 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_375 = TRUE; break;
		}
		while(0);
		if( $_375 === TRUE ) { $_399 = TRUE; break; }
		$result = $res_372;
		$this->pos = $pos_372;
		$_397 = NULL;
		do {
			$res_377 = $result;
			$pos_377 = $this->pos;
			$_380 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_380 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_380 = TRUE; break;
			}
			while(0);
			if( $_380 === TRUE ) { $_397 = TRUE; break; }
			$result = $res_377;
			$this->pos = $pos_377;
			$_395 = NULL;
			do {
				$res_382 = $result;
				$pos_382 = $this->pos;
				$_385 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_385 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_385 = TRUE; break;
				}
				while(0);
				if( $_385 === TRUE ) { $_395 = TRUE; break; }
				$result = $res_382;
				$this->pos = $pos_382;
				$_393 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_393 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_393 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_393 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_393 = TRUE; break;
				}
				while(0);
				if( $_393 === TRUE ) { $_395 = TRUE; break; }
				$result = $res_382;
				$this->pos = $pos_382;
				$_395 = FALSE; break;
			}
			while(0);
			if( $_395 === TRUE ) { $_397 = TRUE; break; }
			$result = $res_377;
			$this->pos = $pos_377;
			$_397 = FALSE; break;
		}
		while(0);
		if( $_397 === TRUE ) { $_399 = TRUE; break; }
		$result = $res_372;
		$this->pos = $pos_372;
		$_399 = FALSE; break;
	}
	while(0);
	if( $_399 === TRUE ) { return $this->finalise($result); }
	if( $_399 === FALSE) { return FALSE; }
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
	$_405 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '*') {
			$this->pos += 1;
			$result["text"] .= '*';
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


/* Div: '/' > second_operand:NumericValue > */
protected $match_Div_typestack = array('Div');
function match_Div ($stack = array()) {
	$matchrule = "Div"; $result = $this->construct($matchrule, $matchrule, null);
	$_411 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '/') {
			$this->pos += 1;
			$result["text"] .= '/';
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


/* Mod: '%' > second_operand:NumericValue > */
protected $match_Mod_typestack = array('Mod');
function match_Mod ($stack = array()) {
	$matchrule = "Mod"; $result = $this->construct($matchrule, $matchrule, null);
	$_417 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '%') {
			$this->pos += 1;
			$result["text"] .= '%';
		}
		else { $_417 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_417 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_417 = TRUE; break;
	}
	while(0);
	if( $_417 === TRUE ) { return $this->finalise($result); }
	if( $_417 === FALSE) { return FALSE; }
}


/* Product: NumericValue > ( Mul | Div | Mod )* */
protected $match_Product_typestack = array('Product');
function match_Product ($stack = array()) {
	$matchrule = "Product"; $result = $this->construct($matchrule, $matchrule, null);
	$_432 = NULL;
	do {
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_432 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_431 = $result;
			$pos_431 = $this->pos;
			$_430 = NULL;
			do {
				$_428 = NULL;
				do {
					$res_421 = $result;
					$pos_421 = $this->pos;
					$matcher = 'match_'.'Mul'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_428 = TRUE; break;
					}
					$result = $res_421;
					$this->pos = $pos_421;
					$_426 = NULL;
					do {
						$res_423 = $result;
						$pos_423 = $this->pos;
						$matcher = 'match_'.'Div'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_426 = TRUE; break;
						}
						$result = $res_423;
						$this->pos = $pos_423;
						$matcher = 'match_'.'Mod'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_426 = TRUE; break;
						}
						$result = $res_423;
						$this->pos = $pos_423;
						$_426 = FALSE; break;
					}
					while(0);
					if( $_426 === TRUE ) { $_428 = TRUE; break; }
					$result = $res_421;
					$this->pos = $pos_421;
					$_428 = FALSE; break;
				}
				while(0);
				if( $_428 === FALSE) { $_430 = FALSE; break; }
				$_430 = TRUE; break;
			}
			while(0);
			if( $_430 === FALSE) {
				$result = $res_431;
				$this->pos = $pos_431;
				unset( $res_431 );
				unset( $pos_431 );
				break;
			}
		}
		$_432 = TRUE; break;
	}
	while(0);
	if( $_432 === TRUE ) { return $this->finalise($result); }
	if( $_432 === FALSE) { return FALSE; }
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
	$_436 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_436 = FALSE; break; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_436 = FALSE; break; }
		$_436 = TRUE; break;
	}
	while(0);
	if( $_436 === TRUE ) { return $this->finalise($result); }
	if( $_436 === FALSE) { return FALSE; }
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
	$_442 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '+') {
			$this->pos += 1;
			$result["text"] .= '+';
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


/* Minus: '-' > second_operand:Product > */
protected $match_Minus_typestack = array('Minus');
function match_Minus ($stack = array()) {
	$matchrule = "Minus"; $result = $this->construct($matchrule, $matchrule, null);
	$_448 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_448 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres, "second_operand" );
		}
		else { $_448 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_448 = TRUE; break;
	}
	while(0);
	if( $_448 === TRUE ) { return $this->finalise($result); }
	if( $_448 === FALSE) { return FALSE; }
}


/* Sum: ( MinusProduct | Product ) > ( Plus | Minus )* */
protected $match_Sum_typestack = array('Sum');
function match_Sum ($stack = array()) {
	$matchrule = "Sum"; $result = $this->construct($matchrule, $matchrule, null);
	$_465 = NULL;
	do {
		$_455 = NULL;
		do {
			$_453 = NULL;
			do {
				$res_450 = $result;
				$pos_450 = $this->pos;
				$matcher = 'match_'.'MinusProduct'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_453 = TRUE; break;
				}
				$result = $res_450;
				$this->pos = $pos_450;
				$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
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
		if( $_455 === FALSE) { $_465 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_464 = $result;
			$pos_464 = $this->pos;
			$_463 = NULL;
			do {
				$_461 = NULL;
				do {
					$res_458 = $result;
					$pos_458 = $this->pos;
					$matcher = 'match_'.'Plus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_461 = TRUE; break;
					}
					$result = $res_458;
					$this->pos = $pos_458;
					$matcher = 'match_'.'Minus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_461 = TRUE; break;
					}
					$result = $res_458;
					$this->pos = $pos_458;
					$_461 = FALSE; break;
				}
				while(0);
				if( $_461 === FALSE) { $_463 = FALSE; break; }
				$_463 = TRUE; break;
			}
			while(0);
			if( $_463 === FALSE) {
				$result = $res_464;
				$this->pos = $pos_464;
				unset( $res_464 );
				unset( $pos_464 );
				break;
			}
		}
		$_465 = TRUE; break;
	}
	while(0);
	if( $_465 === TRUE ) { return $this->finalise($result); }
	if( $_465 === FALSE) { return FALSE; }
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
	$_469 = NULL;
	do {
		$matcher = 'match_'.'Sum'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_469 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_469 = TRUE; break;
	}
	while(0);
	if( $_469 === TRUE ) { return $this->finalise($result); }
	if( $_469 === FALSE) { return FALSE; }
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
	$_485 = NULL;
	do {
		$res_472 = $result;
		$pos_472 = $this->pos;
		$_475 = NULL;
		do {
			$matcher = 'match_'.'StringExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_475 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_475 = TRUE; break;
		}
		while(0);
		if( $_475 === TRUE ) { $_485 = TRUE; break; }
		$result = $res_472;
		$this->pos = $pos_472;
		$_483 = NULL;
		do {
			$res_477 = $result;
			$pos_477 = $this->pos;
			$_480 = NULL;
			do {
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_480 = FALSE; break; }
				$res_479 = $result;
				$pos_479 = $this->pos;
				$matcher = 'match_'.'BooleanBinaryOperatorSign'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$result = $res_479;
					$this->pos = $pos_479;
					$_480 = FALSE; break;
				}
				else {
					$result = $res_479;
					$this->pos = $pos_479;
				}
				$_480 = TRUE; break;
			}
			while(0);
			if( $_480 === TRUE ) { $_483 = TRUE; break; }
			$result = $res_477;
			$this->pos = $pos_477;
			$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_483 = TRUE; break;
			}
			$result = $res_477;
			$this->pos = $pos_477;
			$_483 = FALSE; break;
		}
		while(0);
		if( $_483 === TRUE ) { $_485 = TRUE; break; }
		$result = $res_472;
		$this->pos = $pos_472;
		$_485 = FALSE; break;
	}
	while(0);
	if( $_485 === TRUE ) { return $this->finalise($result); }
	if( $_485 === FALSE) { return FALSE; }
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
	$_496 = NULL;
	do {
		$res_487 = $result;
		$pos_487 = $this->pos;
		$_490 = NULL;
		do {
			$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_490 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_490 = TRUE; break;
		}
		while(0);
		if( $_490 === TRUE ) { $_496 = TRUE; break; }
		$result = $res_487;
		$this->pos = $pos_487;
		$_494 = NULL;
		do {
			$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_494 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_494 = TRUE; break;
		}
		while(0);
		if( $_494 === TRUE ) { $_496 = TRUE; break; }
		$result = $res_487;
		$this->pos = $pos_487;
		$_496 = FALSE; break;
	}
	while(0);
	if( $_496 === TRUE ) { return $this->finalise($result); }
	if( $_496 === FALSE) { return FALSE; }
}

public function ArrayExpression_Function ( &$result, $sub ) {
		$result['expression'] = $sub['function'];
	}

public function ArrayExpression_Variable ( &$result, $sub ) {
		$result['expression'] = $sub['variable'];
	}



}