<?php
require_once 'persons_model_sqlite.php';

$personWithoutId = (object) [
	'firstName' => 'Tyrone',
	'lastName' => 'Kwanston',
	'age' => 44
];

$result = persons_model::create_person($personWithoutId);
echo json_encode($result, JSON_PRETTY_PRINT);