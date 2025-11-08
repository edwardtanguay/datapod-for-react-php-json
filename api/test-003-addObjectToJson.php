<?php
require_once '../scripts/qtools/qfil.php';

$persons = qfil::readJsonFile("../parseddata/persons.json", true);

$person = (object) [
	'firstName' => 'Robert',
	'lastName' => 'Grandium',
	'age' => 21
];

$persons[] = $person;
qfil::saveToJsonFile($persons, "../parseddata/persons.json");
