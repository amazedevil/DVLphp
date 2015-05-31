<?php

require 'vendor/autoload.php';

use DVL\DVLParser;

$parser = new DVLParser( file_get_contents('working.dvl') );
$res = $parser->match_ValidationControl();
if ($res === false) {
	echo 'No match';
} else {
	print_r($res);
}