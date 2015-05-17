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


/* Boolean: 'true' | 'false' */
protected $match_Boolean_typestack = array('Boolean');
function match_Boolean ($stack = array()) {
	$matchrule = "Boolean"; $result = $this->construct($matchrule, $matchrule, null);
	$_14 = NULL;
	do {
		$res_11 = $result;
		$pos_11 = $this->pos;
		if (( $subres = $this->literal( 'true' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$_14 = TRUE; break;
		}
		$result = $res_11;
		$this->pos = $pos_11;
		if (( $subres = $this->literal( 'false' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$_14 = TRUE; break;
		}
		$result = $res_11;
		$this->pos = $pos_11;
		$_14 = FALSE; break;
	}
	while(0);
	if( $_14 === TRUE ) { return $this->finalise($result); }
	if( $_14 === FALSE) { return FALSE; }
}


/* This: 'this' !/[a-zA-Z0-9_]/ */
protected $match_This_typestack = array('This');
function match_This ($stack = array()) {
	$matchrule = "This"; $result = $this->construct($matchrule, $matchrule, null);
	$_18 = NULL;
	do {
		if (( $subres = $this->literal( 'this' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_18 = FALSE; break; }
		$res_17 = $result;
		$pos_17 = $this->pos;
		if (( $subres = $this->rx( '/[a-zA-Z0-9_]/' ) ) !== FALSE) {
			$result["text"] .= $subres;
			$result = $res_17;
			$this->pos = $pos_17;
			$_18 = FALSE; break;
		}
		else {
			$result = $res_17;
			$this->pos = $pos_17;
		}
		$_18 = TRUE; break;
	}
	while(0);
	if( $_18 === TRUE ) { return $this->finalise($result); }
	if( $_18 === FALSE) { return FALSE; }
}


/* Selector: Expression */
protected $match_Selector_typestack = array('Selector');
function match_Selector ($stack = array()) {
	$matchrule = "Selector"; $result = $this->construct($matchrule, $matchrule, null);
	$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
	$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
	if ($subres !== FALSE) {
		$this->store( $result, $subres );
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* Property: '.' Name > */
protected $match_Property_typestack = array('Property');
function match_Property ($stack = array()) {
	$matchrule = "Property"; $result = $this->construct($matchrule, $matchrule, null);
	$_24 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '.') {
			$this->pos += 1;
			$result["text"] .= '.';
		}
		else { $_24 = FALSE; break; }
		$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_24 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_24 = TRUE; break;
	}
	while(0);
	if( $_24 === TRUE ) { return $this->finalise($result); }
	if( $_24 === FALSE) { return FALSE; }
}


/* ArrayElement: '[' > ( Selector )? > ']' > */
protected $match_ArrayElement_typestack = array('ArrayElement');
function match_ArrayElement ($stack = array()) {
	$matchrule = "ArrayElement"; $result = $this->construct($matchrule, $matchrule, null);
	$_34 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '[') {
			$this->pos += 1;
			$result["text"] .= '[';
		}
		else { $_34 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_30 = $result;
		$pos_30 = $this->pos;
		$_29 = NULL;
		do {
			$matcher = 'match_'.'Selector'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_29 = FALSE; break; }
			$_29 = TRUE; break;
		}
		while(0);
		if( $_29 === FALSE) {
			$result = $res_30;
			$this->pos = $pos_30;
			unset( $res_30 );
			unset( $pos_30 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ']') {
			$this->pos += 1;
			$result["text"] .= ']';
		}
		else { $_34 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_34 = TRUE; break;
	}
	while(0);
	if( $_34 === TRUE ) { return $this->finalise($result); }
	if( $_34 === FALSE) { return FALSE; }
}


/* Variable: ( Name | This ) (Property | ArrayElement)* > */
protected $match_Variable_typestack = array('Variable');
function match_Variable ($stack = array()) {
	$matchrule = "Variable"; $result = $this->construct($matchrule, $matchrule, null);
	$_51 = NULL;
	do {
		$_41 = NULL;
		do {
			$_39 = NULL;
			do {
				$res_36 = $result;
				$pos_36 = $this->pos;
				$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_39 = TRUE; break;
				}
				$result = $res_36;
				$this->pos = $pos_36;
				$matcher = 'match_'.'This'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_39 = TRUE; break;
				}
				$result = $res_36;
				$this->pos = $pos_36;
				$_39 = FALSE; break;
			}
			while(0);
			if( $_39 === FALSE) { $_41 = FALSE; break; }
			$_41 = TRUE; break;
		}
		while(0);
		if( $_41 === FALSE) { $_51 = FALSE; break; }
		while (true) {
			$res_49 = $result;
			$pos_49 = $this->pos;
			$_48 = NULL;
			do {
				$_46 = NULL;
				do {
					$res_43 = $result;
					$pos_43 = $this->pos;
					$matcher = 'match_'.'Property'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_46 = TRUE; break;
					}
					$result = $res_43;
					$this->pos = $pos_43;
					$matcher = 'match_'.'ArrayElement'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_46 = TRUE; break;
					}
					$result = $res_43;
					$this->pos = $pos_43;
					$_46 = FALSE; break;
				}
				while(0);
				if( $_46 === FALSE) { $_48 = FALSE; break; }
				$_48 = TRUE; break;
			}
			while(0);
			if( $_48 === FALSE) {
				$result = $res_49;
				$this->pos = $pos_49;
				unset( $res_49 );
				unset( $pos_49 );
				break;
			}
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_51 = TRUE; break;
	}
	while(0);
	if( $_51 === TRUE ) { return $this->finalise($result); }
	if( $_51 === FALSE) { return FALSE; }
}


/* Argument: Expression */
protected $match_Argument_typestack = array('Argument');
function match_Argument ($stack = array()) {
	$matchrule = "Argument"; $result = $this->construct($matchrule, $matchrule, null);
	$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
	$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
	if ($subres !== FALSE) {
		$this->store( $result, $subres );
		return $this->finalise($result);
	}
	else { return FALSE; }
}


/* Function: Name '(' > ( Argument )? > ( ',' > Argument > )* ')' > */
protected $match_Function_typestack = array('Function');
function match_Function ($stack = array()) {
	$matchrule = "Function"; $result = $this->construct($matchrule, $matchrule, null);
	$_69 = NULL;
	do {
		$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_69 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_69 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_59 = $result;
		$pos_59 = $this->pos;
		$_58 = NULL;
		do {
			$matcher = 'match_'.'Argument'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_58 = FALSE; break; }
			$_58 = TRUE; break;
		}
		while(0);
		if( $_58 === FALSE) {
			$result = $res_59;
			$this->pos = $pos_59;
			unset( $res_59 );
			unset( $pos_59 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_66 = $result;
			$pos_66 = $this->pos;
			$_65 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_65 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Argument'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_65 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_65 = TRUE; break;
			}
			while(0);
			if( $_65 === FALSE) {
				$result = $res_66;
				$this->pos = $pos_66;
				unset( $res_66 );
				unset( $pos_66 );
				break;
			}
		}
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_69 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_69 = TRUE; break;
	}
	while(0);
	if( $_69 === TRUE ) { return $this->finalise($result); }
	if( $_69 === FALSE) { return FALSE; }
}


/* Use: '(' > Expression > ')' > ValidationControl > */
protected $match_Use_typestack = array('Use');
function match_Use ($stack = array()) {
	$matchrule = "Use"; $result = $this->construct($matchrule, $matchrule, null);
	$_79 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_79 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_79 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_79 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_79 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_79 = TRUE; break;
	}
	while(0);
	if( $_79 === TRUE ) { return $this->finalise($result); }
	if( $_79 === FALSE) { return FALSE; }
}

public function Use_Expression ( &$result, $sub ) {
		echo "Use - Expression: {$sub['text']}\n";
	}

public function Use_ValidationControl ( &$result, $sub ) {
		echo "Use - ValidationControl: {$sub['text']}\n";
	}

/* Ternary: '(' > BooleanExpression > ')' > '?' > ValidationControl > ( ':' > ValidationControl > )? */
protected $match_Ternary_typestack = array('Ternary');
function match_Ternary ($stack = array()) {
	$matchrule = "Ternary"; $result = $this->construct($matchrule, $matchrule, null);
	$_97 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_97 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_97 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_97 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == '?') {
			$this->pos += 1;
			$result["text"] .= '?';
		}
		else { $_97 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_97 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_96 = $result;
		$pos_96 = $this->pos;
		$_95 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == ':') {
				$this->pos += 1;
				$result["text"] .= ':';
			}
			else { $_95 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_95 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_95 = TRUE; break;
		}
		while(0);
		if( $_95 === FALSE) {
			$result = $res_96;
			$this->pos = $pos_96;
			unset( $res_96 );
			unset( $pos_96 );
		}
		$_97 = TRUE; break;
	}
	while(0);
	if( $_97 === TRUE ) { return $this->finalise($result); }
	if( $_97 === FALSE) { return FALSE; }
}


/* Foreach: 'foreach' > '(' > ArrayExpression > ')' > ValidationControl > */
protected $match_Foreach_typestack = array('Foreach');
function match_Foreach ($stack = array()) {
	$matchrule = "Foreach"; $result = $this->construct($matchrule, $matchrule, null);
	$_109 = NULL;
	do {
		if (( $subres = $this->literal( 'foreach' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_109 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_109 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ArrayExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_109 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_109 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_109 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_109 = TRUE; break;
	}
	while(0);
	if( $_109 === TRUE ) { return $this->finalise($result); }
	if( $_109 === FALSE) { return FALSE; }
}


/* Group: '{' > ValidationControl > ( ',' > ValidationControl > )* '}' > */
protected $match_Group_typestack = array('Group');
function match_Group ($stack = array()) {
	$matchrule = "Group"; $result = $this->construct($matchrule, $matchrule, null);
	$_123 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '{') {
			$this->pos += 1;
			$result["text"] .= '{';
		}
		else { $_123 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_123 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_120 = $result;
			$pos_120 = $this->pos;
			$_119 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_119 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_119 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_119 = TRUE; break;
			}
			while(0);
			if( $_119 === FALSE) {
				$result = $res_120;
				$this->pos = $pos_120;
				unset( $res_120 );
				unset( $pos_120 );
				break;
			}
		}
		if (substr($this->string,$this->pos,1) == '}') {
			$this->pos += 1;
			$result["text"] .= '}';
		}
		else { $_123 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_123 = TRUE; break;
	}
	while(0);
	if( $_123 === TRUE ) { return $this->finalise($result); }
	if( $_123 === FALSE) { return FALSE; }
}

public function Group_ValidationControl ( &$result, $sub ) {
		echo "ValidationControl: {$sub['text']}\n";
	}

/* ValidationControl: Group | Ternary | Use | Validation */
protected $match_ValidationControl_typestack = array('ValidationControl');
function match_ValidationControl ($stack = array()) {
	$matchrule = "ValidationControl"; $result = $this->construct($matchrule, $matchrule, null);
	$_136 = NULL;
	do {
		$res_125 = $result;
		$pos_125 = $this->pos;
		$matcher = 'match_'.'Group'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_136 = TRUE; break;
		}
		$result = $res_125;
		$this->pos = $pos_125;
		$_134 = NULL;
		do {
			$res_127 = $result;
			$pos_127 = $this->pos;
			$matcher = 'match_'.'Ternary'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_134 = TRUE; break;
			}
			$result = $res_127;
			$this->pos = $pos_127;
			$_132 = NULL;
			do {
				$res_129 = $result;
				$pos_129 = $this->pos;
				$matcher = 'match_'.'Use'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_132 = TRUE; break;
				}
				$result = $res_129;
				$this->pos = $pos_129;
				$matcher = 'match_'.'Validation'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_132 = TRUE; break;
				}
				$result = $res_129;
				$this->pos = $pos_129;
				$_132 = FALSE; break;
			}
			while(0);
			if( $_132 === TRUE ) { $_134 = TRUE; break; }
			$result = $res_127;
			$this->pos = $pos_127;
			$_134 = FALSE; break;
		}
		while(0);
		if( $_134 === TRUE ) { $_136 = TRUE; break; }
		$result = $res_125;
		$this->pos = $pos_125;
		$_136 = FALSE; break;
	}
	while(0);
	if( $_136 === TRUE ) { return $this->finalise($result); }
	if( $_136 === FALSE) { return FALSE; }
}

public function ValidationControl_Group ( &$result, $sub ) {
		echo "Group: {$sub['text']}\n";
	}

public function ValidationControl_Ternary ( &$result, $sub ) {
		echo "Ternary: {$sub['text']}\n";
	}

public function ValidationControl_Use ( &$result, $sub ) {
		echo "Use: {$sub['text']}\n";
	}

/* Validation: Expression ( > '@' > String )? > */
protected $match_Validation_typestack = array('Validation');
function match_Validation ($stack = array()) {
	$matchrule = "Validation"; $result = $this->construct($matchrule, $matchrule, null);
	$_146 = NULL;
	do {
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_146 = FALSE; break; }
		$res_144 = $result;
		$pos_144 = $this->pos;
		$_143 = NULL;
		do {
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			if (substr($this->string,$this->pos,1) == '@') {
				$this->pos += 1;
				$result["text"] .= '@';
			}
			else { $_143 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_143 = FALSE; break; }
			$_143 = TRUE; break;
		}
		while(0);
		if( $_143 === FALSE) {
			$result = $res_144;
			$this->pos = $pos_144;
			unset( $res_144 );
			unset( $pos_144 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_146 = TRUE; break;
	}
	while(0);
	if( $_146 === TRUE ) { return $this->finalise($result); }
	if( $_146 === FALSE) { return FALSE; }
}

public function Validation_Use ( &$result, $sub ) {
		echo "Validation - Use: {$sub['text']}\n";
	}

public function Validation_Expression ( &$result, $sub ) {
		echo "Validation - Expression: {$sub['text']}\n";
	}

public function Validation_Ternary ( &$result, $sub ) {
		echo "Validation - Ternary: {$sub['text']}\n";
	}

/* BooleanValue: Boolean > | Function > | Variable > | '(' > BooleanExpression > ')' > */
protected $match_BooleanValue_typestack = array('BooleanValue');
function match_BooleanValue ($stack = array()) {
	$matchrule = "BooleanValue"; $result = $this->construct($matchrule, $matchrule, null);
	$_175 = NULL;
	do {
		$res_148 = $result;
		$pos_148 = $this->pos;
		$_151 = NULL;
		do {
			$matcher = 'match_'.'Boolean'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_151 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_151 = TRUE; break;
		}
		while(0);
		if( $_151 === TRUE ) { $_175 = TRUE; break; }
		$result = $res_148;
		$this->pos = $pos_148;
		$_173 = NULL;
		do {
			$res_153 = $result;
			$pos_153 = $this->pos;
			$_156 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_156 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_156 = TRUE; break;
			}
			while(0);
			if( $_156 === TRUE ) { $_173 = TRUE; break; }
			$result = $res_153;
			$this->pos = $pos_153;
			$_171 = NULL;
			do {
				$res_158 = $result;
				$pos_158 = $this->pos;
				$_161 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_161 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_161 = TRUE; break;
				}
				while(0);
				if( $_161 === TRUE ) { $_171 = TRUE; break; }
				$result = $res_158;
				$this->pos = $pos_158;
				$_169 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_169 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_169 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_169 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_169 = TRUE; break;
				}
				while(0);
				if( $_169 === TRUE ) { $_171 = TRUE; break; }
				$result = $res_158;
				$this->pos = $pos_158;
				$_171 = FALSE; break;
			}
			while(0);
			if( $_171 === TRUE ) { $_173 = TRUE; break; }
			$result = $res_153;
			$this->pos = $pos_153;
			$_173 = FALSE; break;
		}
		while(0);
		if( $_173 === TRUE ) { $_175 = TRUE; break; }
		$result = $res_148;
		$this->pos = $pos_148;
		$_175 = FALSE; break;
	}
	while(0);
	if( $_175 === TRUE ) { return $this->finalise($result); }
	if( $_175 === FALSE) { return FALSE; }
}


/* EqualityComparableExpression: NumericExpression | BooleanValue | String > */
protected $match_EqualityComparableExpression_typestack = array('EqualityComparableExpression');
function match_EqualityComparableExpression ($stack = array()) {
	$matchrule = "EqualityComparableExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_187 = NULL;
	do {
		$res_177 = $result;
		$pos_177 = $this->pos;
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_187 = TRUE; break;
		}
		$result = $res_177;
		$this->pos = $pos_177;
		$_185 = NULL;
		do {
			$res_179 = $result;
			$pos_179 = $this->pos;
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_185 = TRUE; break;
			}
			$result = $res_179;
			$this->pos = $pos_179;
			$_183 = NULL;
			do {
				$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_183 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_183 = TRUE; break;
			}
			while(0);
			if( $_183 === TRUE ) { $_185 = TRUE; break; }
			$result = $res_179;
			$this->pos = $pos_179;
			$_185 = FALSE; break;
		}
		while(0);
		if( $_185 === TRUE ) { $_187 = TRUE; break; }
		$result = $res_177;
		$this->pos = $pos_177;
		$_187 = FALSE; break;
	}
	while(0);
	if( $_187 === TRUE ) { return $this->finalise($result); }
	if( $_187 === FALSE) { return FALSE; }
}


/* Greater: '>' > NumericExpression > */
protected $match_Greater_typestack = array('Greater');
function match_Greater ($stack = array()) {
	$matchrule = "Greater"; $result = $this->construct($matchrule, $matchrule, null);
	$_193 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
		}
		else { $_193 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_193 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_193 = TRUE; break;
	}
	while(0);
	if( $_193 === TRUE ) { return $this->finalise($result); }
	if( $_193 === FALSE) { return FALSE; }
}


/* Less: '<' > NumericExpression > */
protected $match_Less_typestack = array('Less');
function match_Less ($stack = array()) {
	$matchrule = "Less"; $result = $this->construct($matchrule, $matchrule, null);
	$_199 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '<') {
			$this->pos += 1;
			$result["text"] .= '<';
		}
		else { $_199 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_199 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_199 = TRUE; break;
	}
	while(0);
	if( $_199 === TRUE ) { return $this->finalise($result); }
	if( $_199 === FALSE) { return FALSE; }
}


/* LessOrEqual: '<=' > NumericExpression > */
protected $match_LessOrEqual_typestack = array('LessOrEqual');
function match_LessOrEqual ($stack = array()) {
	$matchrule = "LessOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_205 = NULL;
	do {
		if (( $subres = $this->literal( '<=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_205 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_205 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_205 = TRUE; break;
	}
	while(0);
	if( $_205 === TRUE ) { return $this->finalise($result); }
	if( $_205 === FALSE) { return FALSE; }
}


/* GreaterOrEqual: '>=' > NumericExpression > */
protected $match_GreaterOrEqual_typestack = array('GreaterOrEqual');
function match_GreaterOrEqual ($stack = array()) {
	$matchrule = "GreaterOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_211 = NULL;
	do {
		if (( $subres = $this->literal( '>=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_211 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_211 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_211 = TRUE; break;
	}
	while(0);
	if( $_211 === TRUE ) { return $this->finalise($result); }
	if( $_211 === FALSE) { return FALSE; }
}


/* Equal: '==' > EqualityComparableExpression > */
protected $match_Equal_typestack = array('Equal');
function match_Equal ($stack = array()) {
	$matchrule = "Equal"; $result = $this->construct($matchrule, $matchrule, null);
	$_217 = NULL;
	do {
		if (( $subres = $this->literal( '==' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_217 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_217 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_217 = TRUE; break;
	}
	while(0);
	if( $_217 === TRUE ) { return $this->finalise($result); }
	if( $_217 === FALSE) { return FALSE; }
}


/* NotEqual: '!=' > EqualityComparableExpression > */
protected $match_NotEqual_typestack = array('NotEqual');
function match_NotEqual ($stack = array()) {
	$matchrule = "NotEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_223 = NULL;
	do {
		if (( $subres = $this->literal( '!=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_223 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_223 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_223 = TRUE; break;
	}
	while(0);
	if( $_223 === TRUE ) { return $this->finalise($result); }
	if( $_223 === FALSE) { return FALSE; }
}


/* And: '&&' > BooleanValue > */
protected $match_And_typestack = array('And');
function match_And ($stack = array()) {
	$matchrule = "And"; $result = $this->construct($matchrule, $matchrule, null);
	$_229 = NULL;
	do {
		if (( $subres = $this->literal( '&&' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_229 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_229 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_229 = TRUE; break;
	}
	while(0);
	if( $_229 === TRUE ) { return $this->finalise($result); }
	if( $_229 === FALSE) { return FALSE; }
}


/* Or: '||' > BooleanValue > */
protected $match_Or_typestack = array('Or');
function match_Or ($stack = array()) {
	$matchrule = "Or"; $result = $this->construct($matchrule, $matchrule, null);
	$_235 = NULL;
	do {
		if (( $subres = $this->literal( '||' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_235 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_235 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_235 = TRUE; break;
	}
	while(0);
	if( $_235 === TRUE ) { return $this->finalise($result); }
	if( $_235 === FALSE) { return FALSE; }
}


/* Not: '!' BooleanValue > */
protected $match_Not_typestack = array('Not');
function match_Not ($stack = array()) {
	$matchrule = "Not"; $result = $this->construct($matchrule, $matchrule, null);
	$_240 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '!') {
			$this->pos += 1;
			$result["text"] .= '!';
		}
		else { $_240 = FALSE; break; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_240 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_240 = TRUE; break;
	}
	while(0);
	if( $_240 === TRUE ) { return $this->finalise($result); }
	if( $_240 === FALSE) { return FALSE; }
}


/* BooleanOperation: Not | NumericExpression > ( Greater | Less | LessOrEqual | GreaterOrEqual ) | EqualityComparableExpression > ( Equal | NotEqual ) | BooleanValue > ( And | Or ) > */
protected $match_BooleanOperation_typestack = array('BooleanOperation');
function match_BooleanOperation ($stack = array()) {
	$matchrule = "BooleanOperation"; $result = $this->construct($matchrule, $matchrule, null);
	$_292 = NULL;
	do {
		$res_242 = $result;
		$pos_242 = $this->pos;
		$matcher = 'match_'.'Not'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_292 = TRUE; break;
		}
		$result = $res_242;
		$this->pos = $pos_242;
		$_290 = NULL;
		do {
			$res_244 = $result;
			$pos_244 = $this->pos;
			$_262 = NULL;
			do {
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_262 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_260 = NULL;
				do {
					$_258 = NULL;
					do {
						$res_247 = $result;
						$pos_247 = $this->pos;
						$matcher = 'match_'.'Greater'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_258 = TRUE; break;
						}
						$result = $res_247;
						$this->pos = $pos_247;
						$_256 = NULL;
						do {
							$res_249 = $result;
							$pos_249 = $this->pos;
							$matcher = 'match_'.'Less'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_256 = TRUE; break;
							}
							$result = $res_249;
							$this->pos = $pos_249;
							$_254 = NULL;
							do {
								$res_251 = $result;
								$pos_251 = $this->pos;
								$matcher = 'match_'.'LessOrEqual'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_254 = TRUE; break;
								}
								$result = $res_251;
								$this->pos = $pos_251;
								$matcher = 'match_'.'GreaterOrEqual'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_254 = TRUE; break;
								}
								$result = $res_251;
								$this->pos = $pos_251;
								$_254 = FALSE; break;
							}
							while(0);
							if( $_254 === TRUE ) { $_256 = TRUE; break; }
							$result = $res_249;
							$this->pos = $pos_249;
							$_256 = FALSE; break;
						}
						while(0);
						if( $_256 === TRUE ) { $_258 = TRUE; break; }
						$result = $res_247;
						$this->pos = $pos_247;
						$_258 = FALSE; break;
					}
					while(0);
					if( $_258 === FALSE) { $_260 = FALSE; break; }
					$_260 = TRUE; break;
				}
				while(0);
				if( $_260 === FALSE) { $_262 = FALSE; break; }
				$_262 = TRUE; break;
			}
			while(0);
			if( $_262 === TRUE ) { $_290 = TRUE; break; }
			$result = $res_244;
			$this->pos = $pos_244;
			$_288 = NULL;
			do {
				$res_264 = $result;
				$pos_264 = $this->pos;
				$_274 = NULL;
				do {
					$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_274 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_272 = NULL;
					do {
						$_270 = NULL;
						do {
							$res_267 = $result;
							$pos_267 = $this->pos;
							$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_270 = TRUE; break;
							}
							$result = $res_267;
							$this->pos = $pos_267;
							$matcher = 'match_'.'NotEqual'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_270 = TRUE; break;
							}
							$result = $res_267;
							$this->pos = $pos_267;
							$_270 = FALSE; break;
						}
						while(0);
						if( $_270 === FALSE) { $_272 = FALSE; break; }
						$_272 = TRUE; break;
					}
					while(0);
					if( $_272 === FALSE) { $_274 = FALSE; break; }
					$_274 = TRUE; break;
				}
				while(0);
				if( $_274 === TRUE ) { $_288 = TRUE; break; }
				$result = $res_264;
				$this->pos = $pos_264;
				$_286 = NULL;
				do {
					$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_286 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_283 = NULL;
					do {
						$_281 = NULL;
						do {
							$res_278 = $result;
							$pos_278 = $this->pos;
							$matcher = 'match_'.'And'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_281 = TRUE; break;
							}
							$result = $res_278;
							$this->pos = $pos_278;
							$matcher = 'match_'.'Or'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_281 = TRUE; break;
							}
							$result = $res_278;
							$this->pos = $pos_278;
							$_281 = FALSE; break;
						}
						while(0);
						if( $_281 === FALSE) { $_283 = FALSE; break; }
						$_283 = TRUE; break;
					}
					while(0);
					if( $_283 === FALSE) { $_286 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_286 = TRUE; break;
				}
				while(0);
				if( $_286 === TRUE ) { $_288 = TRUE; break; }
				$result = $res_264;
				$this->pos = $pos_264;
				$_288 = FALSE; break;
			}
			while(0);
			if( $_288 === TRUE ) { $_290 = TRUE; break; }
			$result = $res_244;
			$this->pos = $pos_244;
			$_290 = FALSE; break;
		}
		while(0);
		if( $_290 === TRUE ) { $_292 = TRUE; break; }
		$result = $res_242;
		$this->pos = $pos_242;
		$_292 = FALSE; break;
	}
	while(0);
	if( $_292 === TRUE ) { return $this->finalise($result); }
	if( $_292 === FALSE) { return FALSE; }
}


/* BooleanExpression: BooleanOperation | BooleanValue > */
protected $match_BooleanExpression_typestack = array('BooleanExpression');
function match_BooleanExpression ($stack = array()) {
	$matchrule = "BooleanExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_300 = NULL;
	do {
		$res_294 = $result;
		$pos_294 = $this->pos;
		$matcher = 'match_'.'BooleanOperation'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_300 = TRUE; break;
		}
		$result = $res_294;
		$this->pos = $pos_294;
		$_298 = NULL;
		do {
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_298 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_298 = TRUE; break;
		}
		while(0);
		if( $_298 === TRUE ) { $_300 = TRUE; break; }
		$result = $res_294;
		$this->pos = $pos_294;
		$_300 = FALSE; break;
	}
	while(0);
	if( $_300 === TRUE ) { return $this->finalise($result); }
	if( $_300 === FALSE) { return FALSE; }
}


/* NumericValue: Number > | Function > | Variable > | '(' > NumericExpression > ')' > */
protected $match_NumericValue_typestack = array('NumericValue');
function match_NumericValue ($stack = array()) {
	$matchrule = "NumericValue"; $result = $this->construct($matchrule, $matchrule, null);
	$_329 = NULL;
	do {
		$res_302 = $result;
		$pos_302 = $this->pos;
		$_305 = NULL;
		do {
			$matcher = 'match_'.'Number'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_305 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_305 = TRUE; break;
		}
		while(0);
		if( $_305 === TRUE ) { $_329 = TRUE; break; }
		$result = $res_302;
		$this->pos = $pos_302;
		$_327 = NULL;
		do {
			$res_307 = $result;
			$pos_307 = $this->pos;
			$_310 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_310 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_310 = TRUE; break;
			}
			while(0);
			if( $_310 === TRUE ) { $_327 = TRUE; break; }
			$result = $res_307;
			$this->pos = $pos_307;
			$_325 = NULL;
			do {
				$res_312 = $result;
				$pos_312 = $this->pos;
				$_315 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_315 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_315 = TRUE; break;
				}
				while(0);
				if( $_315 === TRUE ) { $_325 = TRUE; break; }
				$result = $res_312;
				$this->pos = $pos_312;
				$_323 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_323 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_323 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_323 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_323 = TRUE; break;
				}
				while(0);
				if( $_323 === TRUE ) { $_325 = TRUE; break; }
				$result = $res_312;
				$this->pos = $pos_312;
				$_325 = FALSE; break;
			}
			while(0);
			if( $_325 === TRUE ) { $_327 = TRUE; break; }
			$result = $res_307;
			$this->pos = $pos_307;
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


/* Mul: '*' > NumericValue > */
protected $match_Mul_typestack = array('Mul');
function match_Mul ($stack = array()) {
	$matchrule = "Mul"; $result = $this->construct($matchrule, $matchrule, null);
	$_335 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '*') {
			$this->pos += 1;
			$result["text"] .= '*';
		}
		else { $_335 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_335 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_335 = TRUE; break;
	}
	while(0);
	if( $_335 === TRUE ) { return $this->finalise($result); }
	if( $_335 === FALSE) { return FALSE; }
}


/* Div: '/' > NumericValue > */
protected $match_Div_typestack = array('Div');
function match_Div ($stack = array()) {
	$matchrule = "Div"; $result = $this->construct($matchrule, $matchrule, null);
	$_341 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '/') {
			$this->pos += 1;
			$result["text"] .= '/';
		}
		else { $_341 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_341 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_341 = TRUE; break;
	}
	while(0);
	if( $_341 === TRUE ) { return $this->finalise($result); }
	if( $_341 === FALSE) { return FALSE; }
}


/* Mod: '%' > NumericValue > */
protected $match_Mod_typestack = array('Mod');
function match_Mod ($stack = array()) {
	$matchrule = "Mod"; $result = $this->construct($matchrule, $matchrule, null);
	$_347 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '%') {
			$this->pos += 1;
			$result["text"] .= '%';
		}
		else { $_347 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_347 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_347 = TRUE; break;
	}
	while(0);
	if( $_347 === TRUE ) { return $this->finalise($result); }
	if( $_347 === FALSE) { return FALSE; }
}


/* Product: NumericValue > ( Mul | Div | Mod )* */
protected $match_Product_typestack = array('Product');
function match_Product ($stack = array()) {
	$matchrule = "Product"; $result = $this->construct($matchrule, $matchrule, null);
	$_362 = NULL;
	do {
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_362 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_361 = $result;
			$pos_361 = $this->pos;
			$_360 = NULL;
			do {
				$_358 = NULL;
				do {
					$res_351 = $result;
					$pos_351 = $this->pos;
					$matcher = 'match_'.'Mul'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_358 = TRUE; break;
					}
					$result = $res_351;
					$this->pos = $pos_351;
					$_356 = NULL;
					do {
						$res_353 = $result;
						$pos_353 = $this->pos;
						$matcher = 'match_'.'Div'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_356 = TRUE; break;
						}
						$result = $res_353;
						$this->pos = $pos_353;
						$matcher = 'match_'.'Mod'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_356 = TRUE; break;
						}
						$result = $res_353;
						$this->pos = $pos_353;
						$_356 = FALSE; break;
					}
					while(0);
					if( $_356 === TRUE ) { $_358 = TRUE; break; }
					$result = $res_351;
					$this->pos = $pos_351;
					$_358 = FALSE; break;
				}
				while(0);
				if( $_358 === FALSE) { $_360 = FALSE; break; }
				$_360 = TRUE; break;
			}
			while(0);
			if( $_360 === FALSE) {
				$result = $res_361;
				$this->pos = $pos_361;
				unset( $res_361 );
				unset( $pos_361 );
				break;
			}
		}
		$_362 = TRUE; break;
	}
	while(0);
	if( $_362 === TRUE ) { return $this->finalise($result); }
	if( $_362 === FALSE) { return FALSE; }
}


/* Plus: '+' > Product > */
protected $match_Plus_typestack = array('Plus');
function match_Plus ($stack = array()) {
	$matchrule = "Plus"; $result = $this->construct($matchrule, $matchrule, null);
	$_368 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '+') {
			$this->pos += 1;
			$result["text"] .= '+';
		}
		else { $_368 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_368 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_368 = TRUE; break;
	}
	while(0);
	if( $_368 === TRUE ) { return $this->finalise($result); }
	if( $_368 === FALSE) { return FALSE; }
}


/* Minus: '-' > Product > */
protected $match_Minus_typestack = array('Minus');
function match_Minus ($stack = array()) {
	$matchrule = "Minus"; $result = $this->construct($matchrule, $matchrule, null);
	$_374 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_374 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_374 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_374 = TRUE; break;
	}
	while(0);
	if( $_374 === TRUE ) { return $this->finalise($result); }
	if( $_374 === FALSE) { return FALSE; }
}


/* Sum: ( '-' Product | Product ) > ( Plus | Minus )* */
protected $match_Sum_typestack = array('Sum');
function match_Sum ($stack = array()) {
	$matchrule = "Sum"; $result = $this->construct($matchrule, $matchrule, null);
	$_394 = NULL;
	do {
		$_384 = NULL;
		do {
			$_382 = NULL;
			do {
				$res_376 = $result;
				$pos_376 = $this->pos;
				$_379 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '-') {
						$this->pos += 1;
						$result["text"] .= '-';
					}
					else { $_379 = FALSE; break; }
					$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_379 = FALSE; break; }
					$_379 = TRUE; break;
				}
				while(0);
				if( $_379 === TRUE ) { $_382 = TRUE; break; }
				$result = $res_376;
				$this->pos = $pos_376;
				$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_382 = TRUE; break;
				}
				$result = $res_376;
				$this->pos = $pos_376;
				$_382 = FALSE; break;
			}
			while(0);
			if( $_382 === FALSE) { $_384 = FALSE; break; }
			$_384 = TRUE; break;
		}
		while(0);
		if( $_384 === FALSE) { $_394 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_393 = $result;
			$pos_393 = $this->pos;
			$_392 = NULL;
			do {
				$_390 = NULL;
				do {
					$res_387 = $result;
					$pos_387 = $this->pos;
					$matcher = 'match_'.'Plus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_390 = TRUE; break;
					}
					$result = $res_387;
					$this->pos = $pos_387;
					$matcher = 'match_'.'Minus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_390 = TRUE; break;
					}
					$result = $res_387;
					$this->pos = $pos_387;
					$_390 = FALSE; break;
				}
				while(0);
				if( $_390 === FALSE) { $_392 = FALSE; break; }
				$_392 = TRUE; break;
			}
			while(0);
			if( $_392 === FALSE) {
				$result = $res_393;
				$this->pos = $pos_393;
				unset( $res_393 );
				unset( $pos_393 );
				break;
			}
		}
		$_394 = TRUE; break;
	}
	while(0);
	if( $_394 === TRUE ) { return $this->finalise($result); }
	if( $_394 === FALSE) { return FALSE; }
}


/* NumericExpression: Sum > */
protected $match_NumericExpression_typestack = array('NumericExpression');
function match_NumericExpression ($stack = array()) {
	$matchrule = "NumericExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_398 = NULL;
	do {
		$matcher = 'match_'.'Sum'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_398 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_398 = TRUE; break;
	}
	while(0);
	if( $_398 === TRUE ) { return $this->finalise($result); }
	if( $_398 === FALSE) { return FALSE; }
}


/* Expression: String > | !BooleanOperation NumericExpression | BooleanExpression */
protected $match_Expression_typestack = array('Expression');
function match_Expression ($stack = array()) {
	$matchrule = "Expression"; $result = $this->construct($matchrule, $matchrule, null);
	$_413 = NULL;
	do {
		$res_400 = $result;
		$pos_400 = $this->pos;
		$_403 = NULL;
		do {
			$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_403 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_403 = TRUE; break;
		}
		while(0);
		if( $_403 === TRUE ) { $_413 = TRUE; break; }
		$result = $res_400;
		$this->pos = $pos_400;
		$_411 = NULL;
		do {
			$res_405 = $result;
			$pos_405 = $this->pos;
			$_408 = NULL;
			do {
				$res_406 = $result;
				$pos_406 = $this->pos;
				$matcher = 'match_'.'BooleanOperation'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$result = $res_406;
					$this->pos = $pos_406;
					$_408 = FALSE; break;
				}
				else {
					$result = $res_406;
					$this->pos = $pos_406;
				}
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_408 = FALSE; break; }
				$_408 = TRUE; break;
			}
			while(0);
			if( $_408 === TRUE ) { $_411 = TRUE; break; }
			$result = $res_405;
			$this->pos = $pos_405;
			$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_411 = TRUE; break;
			}
			$result = $res_405;
			$this->pos = $pos_405;
			$_411 = FALSE; break;
		}
		while(0);
		if( $_411 === TRUE ) { $_413 = TRUE; break; }
		$result = $res_400;
		$this->pos = $pos_400;
		$_413 = FALSE; break;
	}
	while(0);
	if( $_413 === TRUE ) { return $this->finalise($result); }
	if( $_413 === FALSE) { return FALSE; }
}

public function Expression_NumericExpression ( &$result, $sub ) {
		echo "Expression - NumericExpression: {$sub['text']}\n";
	}

public function Expression_BooleanExpression ( &$result, $sub ) {
		echo "Expression - BooleanExpression: {$sub['text']}\n";
	}

public function Expression_String ( &$result, $sub ) {
		echo "Expression - String: {$sub['text']}\n";
	}

/* ArrayExpression: Function > | Variable > */
protected $match_ArrayExpression_typestack = array('ArrayExpression');
function match_ArrayExpression ($stack = array()) {
	$matchrule = "ArrayExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_424 = NULL;
	do {
		$res_415 = $result;
		$pos_415 = $this->pos;
		$_418 = NULL;
		do {
			$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_418 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_418 = TRUE; break;
		}
		while(0);
		if( $_418 === TRUE ) { $_424 = TRUE; break; }
		$result = $res_415;
		$this->pos = $pos_415;
		$_422 = NULL;
		do {
			$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_422 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_422 = TRUE; break;
		}
		while(0);
		if( $_422 === TRUE ) { $_424 = TRUE; break; }
		$result = $res_415;
		$this->pos = $pos_415;
		$_424 = FALSE; break;
	}
	while(0);
	if( $_424 === TRUE ) { return $this->finalise($result); }
	if( $_424 === FALSE) { return FALSE; }
}




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