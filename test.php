<?php

require 'vendor/autoload.php';

use DVL\DVLValidator;

$validator = new DVLValidator( file_get_contents('working.dvl') );
try {
    $validator->validate([
        'var_num' => 4,
        'var_str' => 'test string',
        'var_arr' => [ 1, 2, 1, 2 ],
        'var_assoc' => [ 'test' => 2, 'key2' => 3 ]
    ]);
} catch (Exception $e) {
    print_r($e);
}