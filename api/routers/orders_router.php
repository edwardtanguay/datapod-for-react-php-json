<?php
require_once __DIR__ . '/../models/orders_model_sqlite.php';

class orders_router
{
	public static function process($request_method, $request_uri)
	{
		// GET /orders/{suuid}
		if ($request_method === 'GET' && preg_match('#^/orders/([^/]+)$#', $request_uri, $matches)) {
			$suuid = $matches[1];
			header('Content-Type: application/json');
			$order = orders_model::get_order_by_suuid($suuid);
			if ($order === null) {
				http_response_code(404);
				echo json_encode(['error' => 'Order not found']);
				exit;
			}
			echo json_encode($order, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

			// DELETE /orders/{suuid}
		} else if ($request_method === 'DELETE' && preg_match('#^/orders/([^/]+)$#', $request_uri, $matches)) {
			$suuid = $matches[1];
			header('Content-Type: application/json');
			$result = orders_model::delete_order_by_suuid($suuid);
			if ($result) {
				http_response_code(204);
				exit;
			} else {
				http_response_code(404);
				echo json_encode(['error' => 'Order not found, cannot delete']);
				exit;
			}

		} else {
			switch ($request_method . ' ' . $request_uri) {

				// GET /orders
				case 'GET /orders':
					header('Content-Type: application/json');
					$orders = orders_model::get_all_orders();
					echo json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
					break;

				// PUT /orders
				case 'PUT /orders':
					$input = file_get_contents('php://input');
					$data = json_decode($input, true);

					$orderToUpdate = (object) [
						'suuid' => $data['suuid'],
						'customer_suuid' => $data['customer_suuid'],
						'article_suuid' => $data['article_suuid'],
						'amount' => (int) $data['amount']
					];

					$updatedOrder = orders_model::update_order_by_suuid($orderToUpdate);

					header('Content-Type: application/json');
					if ($updatedOrder === null) {
						http_response_code(404);
						echo json_encode([
							'error' => 'Order not found',
							'message' => 'Failed to update order in the database'
						], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
						exit;
					}

					echo json_encode([
						'message' => 'Order updated successfully',
						'order' => $updatedOrder
					], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
					break;

				// POST /orders
				case 'POST /orders':
					$input = file_get_contents('php://input');
					$data = json_decode($input, true);

					$orderWithoutSuuid = (object) [
						'customer_suuid' => $data['customer_suuid'],
						'article_suuid' => $data['article_suuid'],
						'amount' => (int) $data['amount']
					];

					$orderAdded = orders_model::create_order($orderWithoutSuuid);

					if ($orderAdded === null) {
						header('Content-Type: application/json');
						http_response_code(400);
						echo json_encode([
							'error' => 'Cannot create order',
							'message' => 'Failed to create order in the database'
						], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
						exit;
					}

					header('Content-Type: application/json');
					http_response_code(201);
					echo json_encode([
						'message' => 'Order created successfully',
						'order' => $orderAdded
					], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
					break;

				default:
					http_response_code(404);
					echo json_encode(['error' => 'Endpoint not found']);
			}
		}
	}
}