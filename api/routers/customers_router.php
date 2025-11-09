<?php
require_once __DIR__ . '/../models/customers_model_sqlite.php';

class customers_router
{
	public static function process($request_method, $request_uri)
	{
		// GET /customers/{suuid}
		if ($request_method === 'GET' && preg_match('#^/customers/([^/]+)$#', $request_uri, $matches)) {
			$suuid = $matches[1];
			header('Content-Type: application/json');
			$customer = customers_model::get_customer_by_suuid($suuid);
			if ($customer === null) {
				http_response_code(404);
				echo json_encode(['error' => 'Customer not found']);
				exit;
			}
			echo json_encode($customer, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

			// DELETE /customers/{suuid}
		} else if ($request_method === 'DELETE' && preg_match('#^/customers/([^/]+)$#', $request_uri, $matches)) {
			$suuid = $matches[1];
			header('Content-Type: application/json');
			$result = customers_model::delete_customer_by_suuid($suuid);
			if ($result) {
				http_response_code(204);
				exit;
			} else {
				http_response_code(404);
				echo json_encode(['error' => 'Customer not found, cannot delete']);
				exit;
			}

		} else {
			switch ($request_method . ' ' . $request_uri) {

				// GET /customers
				case 'GET /customers':
					header('Content-Type: application/json');
					$customers = customers_model::get_all_customers();
					echo json_encode($customers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
					break;

				// PUT /customers
				case 'PUT /customers':
					$input = file_get_contents('php://input');
					$data = json_decode($input, true);

					$customerToUpdate = (object) [
						'suuid' => $data['suuid'],
						'firstName' => $data['firstName'],
						'lastName' => $data['lastName'],
						'address' => $data['address'],
						'zipcode' => $data['zipcode'],
						'city' => $data['city']
					];

					$updatedCustomer = customers_model::update_customer_by_suuid($customerToUpdate);

					header('Content-Type: application/json');
					if ($updatedCustomer === null) {
						http_response_code(404);
						echo json_encode([
							'error' => 'Customer not found',
							'message' => 'Failed to update customer in the database'
						], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
						exit;
					}

					echo json_encode([
						'message' => 'Customer updated successfully',
						'customer' => $updatedCustomer
					], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
					break;

				// POST /customers
				case 'POST /customers':
					$input = file_get_contents('php://input');
					$data = json_decode($input, true);

					$customerWithoutSuuid = (object) [
						'firstName' => $data['firstName'],
						'lastName' => $data['lastName'],
						'address' => $data['address'],
						'zipcode' => $data['zipcode'],
						'city' => $data['city']
					];

					$customerAdded = customers_model::create_customer($customerWithoutSuuid);

					if ($customerAdded === null) {
						header('Content-Type: application/json');
						http_response_code(400);
						echo json_encode([
							'error' => 'Cannot create customer',
							'message' => 'Failed to create customer in the database'
						], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
						exit;
					}

					header('Content-Type: application/json');
					http_response_code(201);
					echo json_encode([
						'message' => 'Customer created successfully',
						'customer' => $customerAdded
					], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
					break;

				default:
					http_response_code(404);
					echo json_encode(['error' => 'Endpoint not found']);
			}
		}
	}
}