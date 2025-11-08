<?php
require_once '../scripts/qtools/qfil.php';

$persons = [];

$person = (object) [
	'firstName' => 'Laura',
	'lastName' => 'Restenberg',
	'age' => 22
];

$persons[] = $person;
qfil::saveToJsonFile($persons, "../parseddata/persons.json");
