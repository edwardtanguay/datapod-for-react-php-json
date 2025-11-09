<?php
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