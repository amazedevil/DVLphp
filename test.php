<?php

require 'vendor/autoload.php';

use DVL\DVLValidator;

$validator = new DVLValidator( file_get_contents('working.dvl') );
try {
	$validator->validate(0);
} catch (Exception $e) {
	print_r($e);
}