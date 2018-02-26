<?php namespace rest;
/**
 * 
 * @author janner
 *
 */
class router {
	/**
	 * determines route to model routters
	 * includes model router id exists
	 * @param string $method
	 * @param string $request
	 * @param string $query_string
	 * @param string $input
	 * @return \rest\response
	 */
	static public function route($method, $request, $query_string, $input) {
		// determine class type from request
		$type = isset($request[0]) ? $request[0] : null;
		$type = preg_replace('/[^a-z\-]/', '', $type); // Removes special chars
		
		$classPath = __DIR__ . "/{$type}/{$type}_router.php";
		if (file_exists($classPath)) {
			require_once ($classPath);
			$class = "rest\\{$type}_router";
			$subRouter = new $class();
			return $subRouter->route($method, $request, $query_string, $input);
		} else {
			$response = new response();
			$response->set_response(404);
			return $response;
		}
	}
}
?>