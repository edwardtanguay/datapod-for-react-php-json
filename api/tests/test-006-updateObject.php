<?php
require_once 'persons_model_json.php';

$updatePerson = (object) [
	'id' => '82Jks3',
	'firstName' => 'Robert--CHANGED',
	'lastName' => 'Grandium',
	'age' => 21
];

persons_model::update_person_by_id($updatePerson);