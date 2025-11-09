<?php
require_once '../scripts/qtools/qfil.php';

$persons = qfil::readJsonFile("../data/persons.json", true);

$person = (object) [
	'firstName' => 'Robert',
	'lastName' => 'Grandium',
	'age' => 21
];

$persons[] = $person;
qfil::saveToJsonFile($persons, "../data/persons.json");
