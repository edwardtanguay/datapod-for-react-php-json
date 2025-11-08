<?php
require_once 'persons_model_json.php';

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// GET /persons/{id}
if ($request_method === 'GET' && preg_match('#^/persons/([^/]+)$#', $request_uri, $matches)) {
	$id = $matches[1];
	header('Content-Type: application/json');
	$person = persons_model::get_person_by_id($id);
	if ($person === null) {
		http_response_code(404);
		echo json_encode(['error' => 'Person not found']);
		exit;
	}
	echo json_encode($person, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	// DELETE /persons/{id}
} else if ($request_method === 'DELETE' && preg_match('#^/persons/([^/]+)$#', $request_uri, $matches)) {
	$id = $matches[1];
	header('Content-Type: application/json');
	$result = persons_model::delete_person_by_id($id);
	if ($result) {
		http_response_code(204);
		exit;
	} else {
		http_response_code(404);
		echo json_encode(['error' => 'Person not found, cannot delete']);
		exit;
	}
} else {
	switch ($request_method . ' ' . $request_uri) {
		// GET /persons
		case 'GET /persons':
			header('Content-Type: application/json');
			$persons = persons_model::get_all_persons();
			echo json_encode($persons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			break;
		// PUT /persons
		case 'PUT /persons':
			// Get the raw PUT data
			$input = file_get_contents('php://input');
			$data = json_decode($input, true);

			$personToUpdate = (object) [
				'id' => $data['id'],
				'firstName' => $data['firstName'],
				'lastName' => $data['lastName'],
				'age' => (int) $data['age']
			];

			$updatedPerson = persons_model::update_person_by_id($personToUpdate);

			header('Content-Type: application/json');
			if ($updatedPerson === null) {
				http_response_code(404);
				echo json_encode([
					'error' => 'Person not found',
					'message' => 'Failed to update person in the database'
				], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
				exit;
			}

			echo json_encode([
				'message' => 'Person updated successfully',
				'person' => $updatedPerson
			], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			break;
		// POST /persons
		case 'POST /persons':
			// Get the raw POST data
			$input = file_get_contents('php://input');
			$data = json_decode($input, true);

			$personWithoutId = (object) [
				'firstName' => $data['firstName'],
				'lastName' => $data['lastName'],
				'age' => (int) $data['age']
			];
			$personAdded = persons_model::create_person($personWithoutId);

			if ($personAdded === null) {
				header('Content-Type: application/json');
				http_response_code(400); // or 500 for server error
				echo json_encode([
					'error' => 'Cannot create person',
					'message' => 'Failed to create person in the database'
				], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
				exit; // Important: stop execution after sending error
			}

			header('Content-Type: application/json');
			http_response_code(201);
			echo json_encode([
				'message' => 'Person created successfully',
				'person' => $personAdded
			], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			break;
		default:
			http_response_code(404);
			echo json_encode(['error' => 'Endpoint not found']);
	}
}