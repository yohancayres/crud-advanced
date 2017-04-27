<?php
/*
 * This is the autoload used in the example.
 */

function __autoload($class_name) {
    require_once 'class/' . $class_name . '.class.php';
}