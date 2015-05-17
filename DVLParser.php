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


/* This: 'this' */
protected $match_This_typestack = array('This');
function match_This ($stack = array()) {
	$matchrule = "This"; $result = $this->construct($matchrule, $matchrule, null);
	if (( $subres = $this->literal( 'this' ) ) !== FALSE) {
		$result["text"] .= $subres;
		return $this->finalise($result);
	}
	else { return FALSE; }
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
	$_21 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '.') {
			$this->pos += 1;
			$result["text"] .= '.';
		}
		else { $_21 = FALSE; break; }
		$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_21 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_21 = TRUE; break;
	}
	while(0);
	if( $_21 === TRUE ) { return $this->finalise($result); }
	if( $_21 === FALSE) { return FALSE; }
}


/* ArrayElement: '[' > ( Selector )? > ']' > */
protected $match_ArrayElement_typestack = array('ArrayElement');
function match_ArrayElement ($stack = array()) {
	$matchrule = "ArrayElement"; $result = $this->construct($matchrule, $matchrule, null);
	$_31 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '[') {
			$this->pos += 1;
			$result["text"] .= '[';
		}
		else { $_31 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_27 = $result;
		$pos_27 = $this->pos;
		$_26 = NULL;
		do {
			$matcher = 'match_'.'Selector'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_26 = FALSE; break; }
			$_26 = TRUE; break;
		}
		while(0);
		if( $_26 === FALSE) {
			$result = $res_27;
			$this->pos = $pos_27;
			unset( $res_27 );
			unset( $pos_27 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ']') {
			$this->pos += 1;
			$result["text"] .= ']';
		}
		else { $_31 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_31 = TRUE; break;
	}
	while(0);
	if( $_31 === TRUE ) { return $this->finalise($result); }
	if( $_31 === FALSE) { return FALSE; }
}


/* Variable: ( Name | This ) (Property | ArrayElement)* > */
protected $match_Variable_typestack = array('Variable');
function match_Variable ($stack = array()) {
	$matchrule = "Variable"; $result = $this->construct($matchrule, $matchrule, null);
	$_48 = NULL;
	do {
		$_38 = NULL;
		do {
			$_36 = NULL;
			do {
				$res_33 = $result;
				$pos_33 = $this->pos;
				$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_36 = TRUE; break;
				}
				$result = $res_33;
				$this->pos = $pos_33;
				$matcher = 'match_'.'This'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_36 = TRUE; break;
				}
				$result = $res_33;
				$this->pos = $pos_33;
				$_36 = FALSE; break;
			}
			while(0);
			if( $_36 === FALSE) { $_38 = FALSE; break; }
			$_38 = TRUE; break;
		}
		while(0);
		if( $_38 === FALSE) { $_48 = FALSE; break; }
		while (true) {
			$res_46 = $result;
			$pos_46 = $this->pos;
			$_45 = NULL;
			do {
				$_43 = NULL;
				do {
					$res_40 = $result;
					$pos_40 = $this->pos;
					$matcher = 'match_'.'Property'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_43 = TRUE; break;
					}
					$result = $res_40;
					$this->pos = $pos_40;
					$matcher = 'match_'.'ArrayElement'; $key = $matcher; $pos = $this->pos;
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
			if( $_45 === FALSE) {
				$result = $res_46;
				$this->pos = $pos_46;
				unset( $res_46 );
				unset( $pos_46 );
				break;
			}
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_48 = TRUE; break;
	}
	while(0);
	if( $_48 === TRUE ) { return $this->finalise($result); }
	if( $_48 === FALSE) { return FALSE; }
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
	$_66 = NULL;
	do {
		$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_66 = FALSE; break; }
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_66 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_56 = $result;
		$pos_56 = $this->pos;
		$_55 = NULL;
		do {
			$matcher = 'match_'.'Argument'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_55 = FALSE; break; }
			$_55 = TRUE; break;
		}
		while(0);
		if( $_55 === FALSE) {
			$result = $res_56;
			$this->pos = $pos_56;
			unset( $res_56 );
			unset( $pos_56 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_63 = $result;
			$pos_63 = $this->pos;
			$_62 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_62 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'Argument'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_62 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
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
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_66 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_66 = TRUE; break;
	}
	while(0);
	if( $_66 === TRUE ) { return $this->finalise($result); }
	if( $_66 === FALSE) { return FALSE; }
}


/* Use: '(' > Expression > ')' > ValidationControl > */
protected $match_Use_typestack = array('Use');
function match_Use ($stack = array()) {
	$matchrule = "Use"; $result = $this->construct($matchrule, $matchrule, null);
	$_76 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_76 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_76 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_76 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_76 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_76 = TRUE; break;
	}
	while(0);
	if( $_76 === TRUE ) { return $this->finalise($result); }
	if( $_76 === FALSE) { return FALSE; }
}

public function Use_Expression ( &$result, $sub ) {
		echo "Use - Expression: {$sub['text']}\n";
	}

public function Use_ValidationControl ( &$result, $sub ) {
		echo "Use - ValidationControl: {$sub['text']}\n";
	}

/* Ternary: '(' > BooleanExpression > ')' > '?' > ValidationControl > ( ':' > ValidationControl > )? > */
protected $match_Ternary_typestack = array('Ternary');
function match_Ternary ($stack = array()) {
	$matchrule = "Ternary"; $result = $this->construct($matchrule, $matchrule, null);
	$_95 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '(') {
			$this->pos += 1;
			$result["text"] .= '(';
		}
		else { $_95 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_95 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == ')') {
			$this->pos += 1;
			$result["text"] .= ')';
		}
		else { $_95 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		if (substr($this->string,$this->pos,1) == '?') {
			$this->pos += 1;
			$result["text"] .= '?';
		}
		else { $_95 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_95 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$res_93 = $result;
		$pos_93 = $this->pos;
		$_92 = NULL;
		do {
			if (substr($this->string,$this->pos,1) == ':') {
				$this->pos += 1;
				$result["text"] .= ':';
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
		if( $_92 === FALSE) {
			$result = $res_93;
			$this->pos = $pos_93;
			unset( $res_93 );
			unset( $pos_93 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_95 = TRUE; break;
	}
	while(0);
	if( $_95 === TRUE ) { return $this->finalise($result); }
	if( $_95 === FALSE) { return FALSE; }
}


/* Group: '{' > ValidationControl > ( ',' > ValidationControl > )* '}' > */
protected $match_Group_typestack = array('Group');
function match_Group ($stack = array()) {
	$matchrule = "Group"; $result = $this->construct($matchrule, $matchrule, null);
	$_109 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '{') {
			$this->pos += 1;
			$result["text"] .= '{';
		}
		else { $_109 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_109 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_106 = $result;
			$pos_106 = $this->pos;
			$_105 = NULL;
			do {
				if (substr($this->string,$this->pos,1) == ',') {
					$this->pos += 1;
					$result["text"] .= ',';
				}
				else { $_105 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$matcher = 'match_'.'ValidationControl'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_105 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_105 = TRUE; break;
			}
			while(0);
			if( $_105 === FALSE) {
				$result = $res_106;
				$this->pos = $pos_106;
				unset( $res_106 );
				unset( $pos_106 );
				break;
			}
		}
		if (substr($this->string,$this->pos,1) == '}') {
			$this->pos += 1;
			$result["text"] .= '}';
		}
		else { $_109 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_109 = TRUE; break;
	}
	while(0);
	if( $_109 === TRUE ) { return $this->finalise($result); }
	if( $_109 === FALSE) { return FALSE; }
}

public function Group_ValidationControl ( &$result, $sub ) {
		echo "ValidationControl: {$sub['text']}\n";
	}

/* ValidationControl: Group | Ternary | Use | Validation */
protected $match_ValidationControl_typestack = array('ValidationControl');
function match_ValidationControl ($stack = array()) {
	$matchrule = "ValidationControl"; $result = $this->construct($matchrule, $matchrule, null);
	$_122 = NULL;
	do {
		$res_111 = $result;
		$pos_111 = $this->pos;
		$matcher = 'match_'.'Group'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_122 = TRUE; break;
		}
		$result = $res_111;
		$this->pos = $pos_111;
		$_120 = NULL;
		do {
			$res_113 = $result;
			$pos_113 = $this->pos;
			$matcher = 'match_'.'Ternary'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_120 = TRUE; break;
			}
			$result = $res_113;
			$this->pos = $pos_113;
			$_118 = NULL;
			do {
				$res_115 = $result;
				$pos_115 = $this->pos;
				$matcher = 'match_'.'Use'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_118 = TRUE; break;
				}
				$result = $res_115;
				$this->pos = $pos_115;
				$matcher = 'match_'.'Validation'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_118 = TRUE; break;
				}
				$result = $res_115;
				$this->pos = $pos_115;
				$_118 = FALSE; break;
			}
			while(0);
			if( $_118 === TRUE ) { $_120 = TRUE; break; }
			$result = $res_113;
			$this->pos = $pos_113;
			$_120 = FALSE; break;
		}
		while(0);
		if( $_120 === TRUE ) { $_122 = TRUE; break; }
		$result = $res_111;
		$this->pos = $pos_111;
		$_122 = FALSE; break;
	}
	while(0);
	if( $_122 === TRUE ) { return $this->finalise($result); }
	if( $_122 === FALSE) { return FALSE; }
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
	$_132 = NULL;
	do {
		$matcher = 'match_'.'Expression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_132 = FALSE; break; }
		$res_130 = $result;
		$pos_130 = $this->pos;
		$_129 = NULL;
		do {
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			if (substr($this->string,$this->pos,1) == '@') {
				$this->pos += 1;
				$result["text"] .= '@';
			}
			else { $_129 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_129 = FALSE; break; }
			$_129 = TRUE; break;
		}
		while(0);
		if( $_129 === FALSE) {
			$result = $res_130;
			$this->pos = $pos_130;
			unset( $res_130 );
			unset( $pos_130 );
		}
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_132 = TRUE; break;
	}
	while(0);
	if( $_132 === TRUE ) { return $this->finalise($result); }
	if( $_132 === FALSE) { return FALSE; }
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
	$_161 = NULL;
	do {
		$res_134 = $result;
		$pos_134 = $this->pos;
		$_137 = NULL;
		do {
			$matcher = 'match_'.'Boolean'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_137 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_137 = TRUE; break;
		}
		while(0);
		if( $_137 === TRUE ) { $_161 = TRUE; break; }
		$result = $res_134;
		$this->pos = $pos_134;
		$_159 = NULL;
		do {
			$res_139 = $result;
			$pos_139 = $this->pos;
			$_142 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_142 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_142 = TRUE; break;
			}
			while(0);
			if( $_142 === TRUE ) { $_159 = TRUE; break; }
			$result = $res_139;
			$this->pos = $pos_139;
			$_157 = NULL;
			do {
				$res_144 = $result;
				$pos_144 = $this->pos;
				$_147 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_147 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_147 = TRUE; break;
				}
				while(0);
				if( $_147 === TRUE ) { $_157 = TRUE; break; }
				$result = $res_144;
				$this->pos = $pos_144;
				$_155 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_155 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_155 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_155 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_155 = TRUE; break;
				}
				while(0);
				if( $_155 === TRUE ) { $_157 = TRUE; break; }
				$result = $res_144;
				$this->pos = $pos_144;
				$_157 = FALSE; break;
			}
			while(0);
			if( $_157 === TRUE ) { $_159 = TRUE; break; }
			$result = $res_139;
			$this->pos = $pos_139;
			$_159 = FALSE; break;
		}
		while(0);
		if( $_159 === TRUE ) { $_161 = TRUE; break; }
		$result = $res_134;
		$this->pos = $pos_134;
		$_161 = FALSE; break;
	}
	while(0);
	if( $_161 === TRUE ) { return $this->finalise($result); }
	if( $_161 === FALSE) { return FALSE; }
}


/* EqualityComparableExpression: NumericExpression | BooleanValue | String > */
protected $match_EqualityComparableExpression_typestack = array('EqualityComparableExpression');
function match_EqualityComparableExpression ($stack = array()) {
	$matchrule = "EqualityComparableExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_173 = NULL;
	do {
		$res_163 = $result;
		$pos_163 = $this->pos;
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_173 = TRUE; break;
		}
		$result = $res_163;
		$this->pos = $pos_163;
		$_171 = NULL;
		do {
			$res_165 = $result;
			$pos_165 = $this->pos;
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_171 = TRUE; break;
			}
			$result = $res_165;
			$this->pos = $pos_165;
			$_169 = NULL;
			do {
				$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_169 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_169 = TRUE; break;
			}
			while(0);
			if( $_169 === TRUE ) { $_171 = TRUE; break; }
			$result = $res_165;
			$this->pos = $pos_165;
			$_171 = FALSE; break;
		}
		while(0);
		if( $_171 === TRUE ) { $_173 = TRUE; break; }
		$result = $res_163;
		$this->pos = $pos_163;
		$_173 = FALSE; break;
	}
	while(0);
	if( $_173 === TRUE ) { return $this->finalise($result); }
	if( $_173 === FALSE) { return FALSE; }
}


/* Greater: '>' > NumericExpression > */
protected $match_Greater_typestack = array('Greater');
function match_Greater ($stack = array()) {
	$matchrule = "Greater"; $result = $this->construct($matchrule, $matchrule, null);
	$_179 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '>') {
			$this->pos += 1;
			$result["text"] .= '>';
		}
		else { $_179 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_179 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_179 = TRUE; break;
	}
	while(0);
	if( $_179 === TRUE ) { return $this->finalise($result); }
	if( $_179 === FALSE) { return FALSE; }
}


/* Less: '<' > NumericExpression > */
protected $match_Less_typestack = array('Less');
function match_Less ($stack = array()) {
	$matchrule = "Less"; $result = $this->construct($matchrule, $matchrule, null);
	$_185 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '<') {
			$this->pos += 1;
			$result["text"] .= '<';
		}
		else { $_185 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_185 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_185 = TRUE; break;
	}
	while(0);
	if( $_185 === TRUE ) { return $this->finalise($result); }
	if( $_185 === FALSE) { return FALSE; }
}


/* LessOrEqual: '<=' > NumericExpression > */
protected $match_LessOrEqual_typestack = array('LessOrEqual');
function match_LessOrEqual ($stack = array()) {
	$matchrule = "LessOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_191 = NULL;
	do {
		if (( $subres = $this->literal( '<=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_191 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_191 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_191 = TRUE; break;
	}
	while(0);
	if( $_191 === TRUE ) { return $this->finalise($result); }
	if( $_191 === FALSE) { return FALSE; }
}


/* GreaterOrEqual: '>=' > NumericExpression > */
protected $match_GreaterOrEqual_typestack = array('GreaterOrEqual');
function match_GreaterOrEqual ($stack = array()) {
	$matchrule = "GreaterOrEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_197 = NULL;
	do {
		if (( $subres = $this->literal( '>=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_197 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_197 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_197 = TRUE; break;
	}
	while(0);
	if( $_197 === TRUE ) { return $this->finalise($result); }
	if( $_197 === FALSE) { return FALSE; }
}


/* Equal: '==' > EqualityComparableExpression > */
protected $match_Equal_typestack = array('Equal');
function match_Equal ($stack = array()) {
	$matchrule = "Equal"; $result = $this->construct($matchrule, $matchrule, null);
	$_203 = NULL;
	do {
		if (( $subres = $this->literal( '==' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_203 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_203 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_203 = TRUE; break;
	}
	while(0);
	if( $_203 === TRUE ) { return $this->finalise($result); }
	if( $_203 === FALSE) { return FALSE; }
}


/* NotEqual: '!=' > EqualityComparableExpression > */
protected $match_NotEqual_typestack = array('NotEqual');
function match_NotEqual ($stack = array()) {
	$matchrule = "NotEqual"; $result = $this->construct($matchrule, $matchrule, null);
	$_209 = NULL;
	do {
		if (( $subres = $this->literal( '!=' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_209 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_209 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_209 = TRUE; break;
	}
	while(0);
	if( $_209 === TRUE ) { return $this->finalise($result); }
	if( $_209 === FALSE) { return FALSE; }
}


/* And: '&&' > BooleanValue > */
protected $match_And_typestack = array('And');
function match_And ($stack = array()) {
	$matchrule = "And"; $result = $this->construct($matchrule, $matchrule, null);
	$_215 = NULL;
	do {
		if (( $subres = $this->literal( '&&' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_215 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_215 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_215 = TRUE; break;
	}
	while(0);
	if( $_215 === TRUE ) { return $this->finalise($result); }
	if( $_215 === FALSE) { return FALSE; }
}


/* Or: '||' > BooleanValue > */
protected $match_Or_typestack = array('Or');
function match_Or ($stack = array()) {
	$matchrule = "Or"; $result = $this->construct($matchrule, $matchrule, null);
	$_221 = NULL;
	do {
		if (( $subres = $this->literal( '||' ) ) !== FALSE) { $result["text"] .= $subres; }
		else { $_221 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_221 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_221 = TRUE; break;
	}
	while(0);
	if( $_221 === TRUE ) { return $this->finalise($result); }
	if( $_221 === FALSE) { return FALSE; }
}


/* Not: '!' BooleanValue > */
protected $match_Not_typestack = array('Not');
function match_Not ($stack = array()) {
	$matchrule = "Not"; $result = $this->construct($matchrule, $matchrule, null);
	$_226 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '!') {
			$this->pos += 1;
			$result["text"] .= '!';
		}
		else { $_226 = FALSE; break; }
		$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_226 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_226 = TRUE; break;
	}
	while(0);
	if( $_226 === TRUE ) { return $this->finalise($result); }
	if( $_226 === FALSE) { return FALSE; }
}


/* BooleanOperation: Not | NumericExpression > ( Greater | Less | LessOrEqual | GreaterOrEqual ) | EqualityComparableExpression > ( Equal | NotEqual ) | BooleanValue > ( And | Or ) > */
protected $match_BooleanOperation_typestack = array('BooleanOperation');
function match_BooleanOperation ($stack = array()) {
	$matchrule = "BooleanOperation"; $result = $this->construct($matchrule, $matchrule, null);
	$_278 = NULL;
	do {
		$res_228 = $result;
		$pos_228 = $this->pos;
		$matcher = 'match_'.'Not'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_278 = TRUE; break;
		}
		$result = $res_228;
		$this->pos = $pos_228;
		$_276 = NULL;
		do {
			$res_230 = $result;
			$pos_230 = $this->pos;
			$_248 = NULL;
			do {
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_248 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_246 = NULL;
				do {
					$_244 = NULL;
					do {
						$res_233 = $result;
						$pos_233 = $this->pos;
						$matcher = 'match_'.'Greater'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_244 = TRUE; break;
						}
						$result = $res_233;
						$this->pos = $pos_233;
						$_242 = NULL;
						do {
							$res_235 = $result;
							$pos_235 = $this->pos;
							$matcher = 'match_'.'Less'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_242 = TRUE; break;
							}
							$result = $res_235;
							$this->pos = $pos_235;
							$_240 = NULL;
							do {
								$res_237 = $result;
								$pos_237 = $this->pos;
								$matcher = 'match_'.'LessOrEqual'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_240 = TRUE; break;
								}
								$result = $res_237;
								$this->pos = $pos_237;
								$matcher = 'match_'.'GreaterOrEqual'; $key = $matcher; $pos = $this->pos;
								$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
								if ($subres !== FALSE) {
									$this->store( $result, $subres );
									$_240 = TRUE; break;
								}
								$result = $res_237;
								$this->pos = $pos_237;
								$_240 = FALSE; break;
							}
							while(0);
							if( $_240 === TRUE ) { $_242 = TRUE; break; }
							$result = $res_235;
							$this->pos = $pos_235;
							$_242 = FALSE; break;
						}
						while(0);
						if( $_242 === TRUE ) { $_244 = TRUE; break; }
						$result = $res_233;
						$this->pos = $pos_233;
						$_244 = FALSE; break;
					}
					while(0);
					if( $_244 === FALSE) { $_246 = FALSE; break; }
					$_246 = TRUE; break;
				}
				while(0);
				if( $_246 === FALSE) { $_248 = FALSE; break; }
				$_248 = TRUE; break;
			}
			while(0);
			if( $_248 === TRUE ) { $_276 = TRUE; break; }
			$result = $res_230;
			$this->pos = $pos_230;
			$_274 = NULL;
			do {
				$res_250 = $result;
				$pos_250 = $this->pos;
				$_260 = NULL;
				do {
					$matcher = 'match_'.'EqualityComparableExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_260 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_258 = NULL;
					do {
						$_256 = NULL;
						do {
							$res_253 = $result;
							$pos_253 = $this->pos;
							$matcher = 'match_'.'Equal'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_256 = TRUE; break;
							}
							$result = $res_253;
							$this->pos = $pos_253;
							$matcher = 'match_'.'NotEqual'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_256 = TRUE; break;
							}
							$result = $res_253;
							$this->pos = $pos_253;
							$_256 = FALSE; break;
						}
						while(0);
						if( $_256 === FALSE) { $_258 = FALSE; break; }
						$_258 = TRUE; break;
					}
					while(0);
					if( $_258 === FALSE) { $_260 = FALSE; break; }
					$_260 = TRUE; break;
				}
				while(0);
				if( $_260 === TRUE ) { $_274 = TRUE; break; }
				$result = $res_250;
				$this->pos = $pos_250;
				$_272 = NULL;
				do {
					$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_272 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_269 = NULL;
					do {
						$_267 = NULL;
						do {
							$res_264 = $result;
							$pos_264 = $this->pos;
							$matcher = 'match_'.'And'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_267 = TRUE; break;
							}
							$result = $res_264;
							$this->pos = $pos_264;
							$matcher = 'match_'.'Or'; $key = $matcher; $pos = $this->pos;
							$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
							if ($subres !== FALSE) {
								$this->store( $result, $subres );
								$_267 = TRUE; break;
							}
							$result = $res_264;
							$this->pos = $pos_264;
							$_267 = FALSE; break;
						}
						while(0);
						if( $_267 === FALSE) { $_269 = FALSE; break; }
						$_269 = TRUE; break;
					}
					while(0);
					if( $_269 === FALSE) { $_272 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_272 = TRUE; break;
				}
				while(0);
				if( $_272 === TRUE ) { $_274 = TRUE; break; }
				$result = $res_250;
				$this->pos = $pos_250;
				$_274 = FALSE; break;
			}
			while(0);
			if( $_274 === TRUE ) { $_276 = TRUE; break; }
			$result = $res_230;
			$this->pos = $pos_230;
			$_276 = FALSE; break;
		}
		while(0);
		if( $_276 === TRUE ) { $_278 = TRUE; break; }
		$result = $res_228;
		$this->pos = $pos_228;
		$_278 = FALSE; break;
	}
	while(0);
	if( $_278 === TRUE ) { return $this->finalise($result); }
	if( $_278 === FALSE) { return FALSE; }
}


/* BooleanExpression: BooleanOperation | BooleanValue > */
protected $match_BooleanExpression_typestack = array('BooleanExpression');
function match_BooleanExpression ($stack = array()) {
	$matchrule = "BooleanExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_286 = NULL;
	do {
		$res_280 = $result;
		$pos_280 = $this->pos;
		$matcher = 'match_'.'BooleanOperation'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) {
			$this->store( $result, $subres );
			$_286 = TRUE; break;
		}
		$result = $res_280;
		$this->pos = $pos_280;
		$_284 = NULL;
		do {
			$matcher = 'match_'.'BooleanValue'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_284 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_284 = TRUE; break;
		}
		while(0);
		if( $_284 === TRUE ) { $_286 = TRUE; break; }
		$result = $res_280;
		$this->pos = $pos_280;
		$_286 = FALSE; break;
	}
	while(0);
	if( $_286 === TRUE ) { return $this->finalise($result); }
	if( $_286 === FALSE) { return FALSE; }
}


/* NumericValue: Number > | Function > | Variable > | '(' > NumericExpression > ')' > */
protected $match_NumericValue_typestack = array('NumericValue');
function match_NumericValue ($stack = array()) {
	$matchrule = "NumericValue"; $result = $this->construct($matchrule, $matchrule, null);
	$_315 = NULL;
	do {
		$res_288 = $result;
		$pos_288 = $this->pos;
		$_291 = NULL;
		do {
			$matcher = 'match_'.'Number'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_291 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_291 = TRUE; break;
		}
		while(0);
		if( $_291 === TRUE ) { $_315 = TRUE; break; }
		$result = $res_288;
		$this->pos = $pos_288;
		$_313 = NULL;
		do {
			$res_293 = $result;
			$pos_293 = $this->pos;
			$_296 = NULL;
			do {
				$matcher = 'match_'.'Function'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_296 = FALSE; break; }
				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
				$_296 = TRUE; break;
			}
			while(0);
			if( $_296 === TRUE ) { $_313 = TRUE; break; }
			$result = $res_293;
			$this->pos = $pos_293;
			$_311 = NULL;
			do {
				$res_298 = $result;
				$pos_298 = $this->pos;
				$_301 = NULL;
				do {
					$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_301 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_301 = TRUE; break;
				}
				while(0);
				if( $_301 === TRUE ) { $_311 = TRUE; break; }
				$result = $res_298;
				$this->pos = $pos_298;
				$_309 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '(') {
						$this->pos += 1;
						$result["text"] .= '(';
					}
					else { $_309 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_309 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					if (substr($this->string,$this->pos,1) == ')') {
						$this->pos += 1;
						$result["text"] .= ')';
					}
					else { $_309 = FALSE; break; }
					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
					$_309 = TRUE; break;
				}
				while(0);
				if( $_309 === TRUE ) { $_311 = TRUE; break; }
				$result = $res_298;
				$this->pos = $pos_298;
				$_311 = FALSE; break;
			}
			while(0);
			if( $_311 === TRUE ) { $_313 = TRUE; break; }
			$result = $res_293;
			$this->pos = $pos_293;
			$_313 = FALSE; break;
		}
		while(0);
		if( $_313 === TRUE ) { $_315 = TRUE; break; }
		$result = $res_288;
		$this->pos = $pos_288;
		$_315 = FALSE; break;
	}
	while(0);
	if( $_315 === TRUE ) { return $this->finalise($result); }
	if( $_315 === FALSE) { return FALSE; }
}


/* Mul: '*' > NumericValue > */
protected $match_Mul_typestack = array('Mul');
function match_Mul ($stack = array()) {
	$matchrule = "Mul"; $result = $this->construct($matchrule, $matchrule, null);
	$_321 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '*') {
			$this->pos += 1;
			$result["text"] .= '*';
		}
		else { $_321 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_321 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_321 = TRUE; break;
	}
	while(0);
	if( $_321 === TRUE ) { return $this->finalise($result); }
	if( $_321 === FALSE) { return FALSE; }
}


/* Div: '/' > NumericValue > */
protected $match_Div_typestack = array('Div');
function match_Div ($stack = array()) {
	$matchrule = "Div"; $result = $this->construct($matchrule, $matchrule, null);
	$_327 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '/') {
			$this->pos += 1;
			$result["text"] .= '/';
		}
		else { $_327 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_327 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_327 = TRUE; break;
	}
	while(0);
	if( $_327 === TRUE ) { return $this->finalise($result); }
	if( $_327 === FALSE) { return FALSE; }
}


/* Mod: '%' > NumericValue > */
protected $match_Mod_typestack = array('Mod');
function match_Mod ($stack = array()) {
	$matchrule = "Mod"; $result = $this->construct($matchrule, $matchrule, null);
	$_333 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '%') {
			$this->pos += 1;
			$result["text"] .= '%';
		}
		else { $_333 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_333 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_333 = TRUE; break;
	}
	while(0);
	if( $_333 === TRUE ) { return $this->finalise($result); }
	if( $_333 === FALSE) { return FALSE; }
}


/* Product: NumericValue > ( Mul | Div | Mod )* */
protected $match_Product_typestack = array('Product');
function match_Product ($stack = array()) {
	$matchrule = "Product"; $result = $this->construct($matchrule, $matchrule, null);
	$_348 = NULL;
	do {
		$matcher = 'match_'.'NumericValue'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_348 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_347 = $result;
			$pos_347 = $this->pos;
			$_346 = NULL;
			do {
				$_344 = NULL;
				do {
					$res_337 = $result;
					$pos_337 = $this->pos;
					$matcher = 'match_'.'Mul'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_344 = TRUE; break;
					}
					$result = $res_337;
					$this->pos = $pos_337;
					$_342 = NULL;
					do {
						$res_339 = $result;
						$pos_339 = $this->pos;
						$matcher = 'match_'.'Div'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_342 = TRUE; break;
						}
						$result = $res_339;
						$this->pos = $pos_339;
						$matcher = 'match_'.'Mod'; $key = $matcher; $pos = $this->pos;
						$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
						if ($subres !== FALSE) {
							$this->store( $result, $subres );
							$_342 = TRUE; break;
						}
						$result = $res_339;
						$this->pos = $pos_339;
						$_342 = FALSE; break;
					}
					while(0);
					if( $_342 === TRUE ) { $_344 = TRUE; break; }
					$result = $res_337;
					$this->pos = $pos_337;
					$_344 = FALSE; break;
				}
				while(0);
				if( $_344 === FALSE) { $_346 = FALSE; break; }
				$_346 = TRUE; break;
			}
			while(0);
			if( $_346 === FALSE) {
				$result = $res_347;
				$this->pos = $pos_347;
				unset( $res_347 );
				unset( $pos_347 );
				break;
			}
		}
		$_348 = TRUE; break;
	}
	while(0);
	if( $_348 === TRUE ) { return $this->finalise($result); }
	if( $_348 === FALSE) { return FALSE; }
}


/* Plus: '+' > Product > */
protected $match_Plus_typestack = array('Plus');
function match_Plus ($stack = array()) {
	$matchrule = "Plus"; $result = $this->construct($matchrule, $matchrule, null);
	$_354 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '+') {
			$this->pos += 1;
			$result["text"] .= '+';
		}
		else { $_354 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_354 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_354 = TRUE; break;
	}
	while(0);
	if( $_354 === TRUE ) { return $this->finalise($result); }
	if( $_354 === FALSE) { return FALSE; }
}


/* Minus: '-' > Product > */
protected $match_Minus_typestack = array('Minus');
function match_Minus ($stack = array()) {
	$matchrule = "Minus"; $result = $this->construct($matchrule, $matchrule, null);
	$_360 = NULL;
	do {
		if (substr($this->string,$this->pos,1) == '-') {
			$this->pos += 1;
			$result["text"] .= '-';
		}
		else { $_360 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_360 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_360 = TRUE; break;
	}
	while(0);
	if( $_360 === TRUE ) { return $this->finalise($result); }
	if( $_360 === FALSE) { return FALSE; }
}


/* Sum: ( '-' Product | Product ) > ( Plus | Minus )* */
protected $match_Sum_typestack = array('Sum');
function match_Sum ($stack = array()) {
	$matchrule = "Sum"; $result = $this->construct($matchrule, $matchrule, null);
	$_380 = NULL;
	do {
		$_370 = NULL;
		do {
			$_368 = NULL;
			do {
				$res_362 = $result;
				$pos_362 = $this->pos;
				$_365 = NULL;
				do {
					if (substr($this->string,$this->pos,1) == '-') {
						$this->pos += 1;
						$result["text"] .= '-';
					}
					else { $_365 = FALSE; break; }
					$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) { $this->store( $result, $subres ); }
					else { $_365 = FALSE; break; }
					$_365 = TRUE; break;
				}
				while(0);
				if( $_365 === TRUE ) { $_368 = TRUE; break; }
				$result = $res_362;
				$this->pos = $pos_362;
				$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$_368 = TRUE; break;
				}
				$result = $res_362;
				$this->pos = $pos_362;
				$_368 = FALSE; break;
			}
			while(0);
			if( $_368 === FALSE) { $_370 = FALSE; break; }
			$_370 = TRUE; break;
		}
		while(0);
		if( $_370 === FALSE) { $_380 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		while (true) {
			$res_379 = $result;
			$pos_379 = $this->pos;
			$_378 = NULL;
			do {
				$_376 = NULL;
				do {
					$res_373 = $result;
					$pos_373 = $this->pos;
					$matcher = 'match_'.'Plus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_376 = TRUE; break;
					}
					$result = $res_373;
					$this->pos = $pos_373;
					$matcher = 'match_'.'Minus'; $key = $matcher; $pos = $this->pos;
					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
					if ($subres !== FALSE) {
						$this->store( $result, $subres );
						$_376 = TRUE; break;
					}
					$result = $res_373;
					$this->pos = $pos_373;
					$_376 = FALSE; break;
				}
				while(0);
				if( $_376 === FALSE) { $_378 = FALSE; break; }
				$_378 = TRUE; break;
			}
			while(0);
			if( $_378 === FALSE) {
				$result = $res_379;
				$this->pos = $pos_379;
				unset( $res_379 );
				unset( $pos_379 );
				break;
			}
		}
		$_380 = TRUE; break;
	}
	while(0);
	if( $_380 === TRUE ) { return $this->finalise($result); }
	if( $_380 === FALSE) { return FALSE; }
}


/* NumericExpression: Sum > */
protected $match_NumericExpression_typestack = array('NumericExpression');
function match_NumericExpression ($stack = array()) {
	$matchrule = "NumericExpression"; $result = $this->construct($matchrule, $matchrule, null);
	$_384 = NULL;
	do {
		$matcher = 'match_'.'Sum'; $key = $matcher; $pos = $this->pos;
		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
		if ($subres !== FALSE) { $this->store( $result, $subres ); }
		else { $_384 = FALSE; break; }
		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
		$_384 = TRUE; break;
	}
	while(0);
	if( $_384 === TRUE ) { return $this->finalise($result); }
	if( $_384 === FALSE) { return FALSE; }
}


/* Expression: String > | !BooleanOperation NumericExpression | BooleanExpression */
protected $match_Expression_typestack = array('Expression');
function match_Expression ($stack = array()) {
	$matchrule = "Expression"; $result = $this->construct($matchrule, $matchrule, null);
	$_399 = NULL;
	do {
		$res_386 = $result;
		$pos_386 = $this->pos;
		$_389 = NULL;
		do {
			$matcher = 'match_'.'String'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) { $this->store( $result, $subres ); }
			else { $_389 = FALSE; break; }
			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
			$_389 = TRUE; break;
		}
		while(0);
		if( $_389 === TRUE ) { $_399 = TRUE; break; }
		$result = $res_386;
		$this->pos = $pos_386;
		$_397 = NULL;
		do {
			$res_391 = $result;
			$pos_391 = $this->pos;
			$_394 = NULL;
			do {
				$res_392 = $result;
				$pos_392 = $this->pos;
				$matcher = 'match_'.'BooleanOperation'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) {
					$this->store( $result, $subres );
					$result = $res_392;
					$this->pos = $pos_392;
					$_394 = FALSE; break;
				}
				else {
					$result = $res_392;
					$this->pos = $pos_392;
				}
				$matcher = 'match_'.'NumericExpression'; $key = $matcher; $pos = $this->pos;
				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
				if ($subres !== FALSE) { $this->store( $result, $subres ); }
				else { $_394 = FALSE; break; }
				$_394 = TRUE; break;
			}
			while(0);
			if( $_394 === TRUE ) { $_397 = TRUE; break; }
			$result = $res_391;
			$this->pos = $pos_391;
			$matcher = 'match_'.'BooleanExpression'; $key = $matcher; $pos = $this->pos;
			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
			if ($subres !== FALSE) {
				$this->store( $result, $subres );
				$_397 = TRUE; break;
			}
			$result = $res_391;
			$this->pos = $pos_391;
			$_397 = FALSE; break;
		}
		while(0);
		if( $_397 === TRUE ) { $_399 = TRUE; break; }
		$result = $res_386;
		$this->pos = $pos_386;
		$_399 = FALSE; break;
	}
	while(0);
	if( $_399 === TRUE ) { return $this->finalise($result); }
	if( $_399 === FALSE) { return FALSE; }
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