<?php namespace rest\classes;
/**
 * 
 * @author janner
 *
 */
class Response {
	public $status;
	public $data;
	public $headers;
	/**
	 * sets content of response
	 * @param integer $status
	 * @param mixed $data
	 * @param array $headers
	 */
	public function set_response($status, $data = null, $headers = []) {
		$this->status = $status;
		$this->data = $data;
		if (! is_array($headers) && $headers) $headers = [
			$headers 
		];
		$this->headers = $headers;
	}
}
?>