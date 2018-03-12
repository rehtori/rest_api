<?php namespace rest;

use rest\classes\HttpResponse;
use rest\classes\ApiUserException;
use rest\inc\Autoloader;

// \error_reporting(-1); // for dev
\error_reporting(0); // for production

// Include the autoloader so we can dynamically include the rest of the classes.
require_once( __DIR__ . '/inc/Autoloader.php' );
Autoloader::register();

// get the HTTP method, path, query and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : null;
$query_string = isset($_SERVER['QUERY_STRING']) && trim($_SERVER['QUERY_STRING']) ? trim($_SERVER['QUERY_STRING']) : null;
$input = json_decode(file_get_contents('php://input'), true);
if (! $input) $input = [];
// determine class type from request
$classType = isset($request[0]) ? $request[0] : null;
$classType = preg_replace('/[^a-z\-]/', '', $classType); // Removes special chars
$class = "rest\\classes\\{$classType}\\Router";

// load response
$http_response = new HttpResponse();
try {
	// determine route to model and get response
	if (class_exists($class)) {
		$http_response->set_from_response($class::route($method, $request, $query_string, $input));
	} else {
		$http_response->set_response(400);
	}
} catch ( ApiUserException $e ) {
	// handle errors for ui
	$http_response->set_from_exception($e);
} catch ( \Exception $e ) {
	// other errors as 500
	$http_response->set_response(500);
}

// echoes the response
$http_response->print_response();
?>