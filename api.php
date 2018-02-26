<?php namespace rest;

\error_reporting(0); // for production
                     
// requirements
require_once 'classes/router.php';
require_once 'classes/http_response.php';
require_once 'classes/response.php';
require_once 'classes/api_user_exception.php';

// get the HTTP method, path, query and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : null;
$query_string = isset($_SERVER['QUERY_STRING']) && trim($_SERVER['QUERY_STRING']) ? trim($_SERVER['QUERY_STRING']) : null;
$input = json_decode(file_get_contents('php://input'), true);
if (! $input) $input = [];

// load response
$http_response = new http_response();
try {
	// determine route to model and get response
	$http_response->set_from_response(router::route($method, $request, $query_string, $input));
} catch ( api_user_exception $e ) {
	// handle errors for ui
	$http_response->set_from_exception($e);
} catch ( \Exception $e ) {
	// other errors as 500
	$http_response->set_response(500);
}

// echoes the response
$http_response->print_response();
?>