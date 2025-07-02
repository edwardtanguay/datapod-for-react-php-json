<?php
require_once '../qtools/qcli.php';
require_once '../developer/classes/learn.php';

// Check if exercise number is provided
if (!isset($argv[1])) {
    qcli::message('Please provide an exercise number, e.g. "npm run learn 001"', 'error');
    exit(0);
}

$exercise = $argv[1];
$methodName = 'ex' . $exercise;

if (method_exists('Learn', $methodName)) {
    qcli::message("EXERCISE $exercise");
    Learn::$methodName();
} else {
    qcli::message("Exercise $exercise not found", 'error');
    exit(0);
}