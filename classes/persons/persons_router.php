<?php namespace rest;

require_once 'classes/response.php';
require_once 'classes/persons/persons_manager.php';
/**
 * 
 * @author janner
 *
 */
class persons_router {
	/**
	 * determine route to methods, return corresponding responses
	 * @param string $method
	 * @param string $request
	 * @param string $query_string
	 * @param string $input
	 * @return \rest\response
	 */
	public function route($method, $request, $query_string, $input) {
		$request_item_id = isset($request[1]) ? $request[1] : null;
		$manager = new persons_manager();
		$response = new response();
		switch ($method) {
			case 'GET' :
				if ($request_item_id) {
					if ($result = $manager->get_person($request_item_id)) {
						$response->set_response(200, $result);
					} else {
						$response->set_response(404, [
							'error_message' => 'user not found' 
						]);
					}
				} else {
					$persons = $manager->get_persons($query_string);
					$response->set_response(200, $persons);
				}
			break;
			case 'POST' :
				if ($request_item_id) {
					$response->set_response(404, [
						'error_message' => 'method not allowed' 
					]);
				} else {
					if ($result = $manager->create_person($input)) {
						$response->set_response(201, null, "Location: /api/persons/{$result}");
					} else {
						$response->set_response(500, [
							'error_message' => 'could not create user' 
						]);
					}
				}
			break;
			case 'PUT' :
				if ($request_item_id) {
					if ($manager->update_person($request_item_id, $input)) {
						$response->set_response(200);
					} else {
						$response->set_response(404);
					}
				} else {
					$response->set_response(405);
				}
			break;
			case 'DELETE' :
				if ($request_item_id) {
					if ($result = $manager->delete_person($request_item_id)) {
						$response->set_response(200);
					} else {
						$response->set_response(404);
					}
				} else {
					$response->set_response(405);
				}
			break;
		}
		
		return $response;
	}
}
?>