<?php
// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	http_response_code(200);
	exit();
}

require_once 'routers/persons_router.php';
require_once 'routers/articles_router.php';
require_once 'routers/orders_router.php';
require_once 'routers/customers_router.php';

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($request_uri, '/persons') === 0) {
	persons_router::process($request_method, $request_uri);
}

if (strpos($request_uri, '/articles') === 0) {
	articles_router::process($request_method, $request_uri);
}

if (strpos($request_uri, '/orders') === 0) {
	orders_router::process($request_method, $request_uri);
}

if (strpos($request_uri, '/customers') === 0) {
	customers_router::process($request_method, $request_uri);
}