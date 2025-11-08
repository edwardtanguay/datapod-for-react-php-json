<?php
require_once 'persons_model_json.php';

$personWithoutId = (object) [
	'firstName' => 'Robert',
	'lastName' => 'Grandium',
	'age' => 21
];

persons_model::create_person($personWithoutId);