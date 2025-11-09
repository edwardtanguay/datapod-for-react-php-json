<?php
require_once __DIR__ . '/../models/articles_model_sqlite.php';

class articles_router
{
	public static function process($request_method, $request_uri)
	{
		// GET /articles/{suuid}
		if ($request_method === 'GET' && preg_match('#^/articles/([^/]+)$#', $request_uri, $matches)) {
			$suuid = $matches[1];
			header('Content-Type: application/json');
			$article = articles_model::get_article_by_suuid($suuid);
			if ($article === null) {
				http_response_code(404);
				echo json_encode(['error' => 'Article not found']);
				exit;
			}
			echo json_encode($article, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

			// DELETE /articles/{suuid}
		} else if ($request_method === 'DELETE' && preg_match('#^/articles/([^/]+)$#', $request_uri, $matches)) {
			$suuid = $matches[1];
			header('Content-Type: application/json');
			$result = articles_model::delete_article_by_suuid($suuid);
			if ($result) {
				http_response_code(204);
				exit;
			} else {
				http_response_code(404);
				echo json_encode(['error' => 'Article not found, cannot delete']);
				exit;
			}

		} else {
			switch ($request_method . ' ' . $request_uri) {
				// GET /articles
				case 'GET /articles':
					header('Content-Type: application/json');
					$articles = articles_model::get_all_articles();
					echo json_encode($articles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
					break;

				// PUT /articles
				case 'PUT /articles':
					$input = file_get_contents('php://input');
					$data = json_decode($input, true);

					$articleToUpdate = (object) [
						'suuid' => $data['suuid'],
						'description' => $data['description'],
						'price' => (float) $data['price']
					];

					$updatedArticle = articles_model::update_article_by_suuid($articleToUpdate);

					header('Content-Type: application/json');
					if ($updatedArticle === null) {
						http_response_code(404);
						echo json_encode([
							'error' => 'Article not found',
							'message' => 'Failed to update article in the database'
						], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
						exit;
					}

					echo json_encode([
						'message' => 'Article updated successfully',
						'article' => $updatedArticle
					], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
					break;

				// POST /articles
				case 'POST /articles':
					$input = file_get_contents('php://input');
					$data = json_decode($input, true);

					$articleWithoutSuuid = (object) [
						'description' => $data['description'],
						'price' => (float) $data['price']
					];

					$articleAdded = articles_model::create_article($articleWithoutSuuid);

					if ($articleAdded === null) {
						header('Content-Type: application/json');
						http_response_code(400);
						echo json_encode([
							'error' => 'Cannot create article',
							'message' => 'Failed to create article in the database'
						], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
						exit;
					}

					header('Content-Type: application/json');
					http_response_code(201);
					echo json_encode([
						'message' => 'Article created successfully',
						'article' => $articleAdded
					], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
					break;

				default:
					http_response_code(404);
					echo json_encode(['error' => 'Endpoint not found']);
			}
		}
	}
}
