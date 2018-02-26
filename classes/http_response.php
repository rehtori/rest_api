<?php namespace rest;

/**
 *
 * @author janner
 *        
 */
class http_response {
	public $status;
	public $status_message;
	public $data;
	public $headers;
	private static $status_messages = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		103 => 'Early Hints',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Payload Too Large',
		414 => 'URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		421 => 'Misdirected Request',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		425 => 'Reserved for WebDAV advanced collections expired proposal',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		451 => 'Unavailable For Legal Reasons',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'Loop Detected',
		510 => 'Not Extended',
		511 => 'Network Authentication Required' 
	];
	/**
	 * sets content of http_response
	 * @param integer $status        	
	 * @param mixed $data        	
	 * @param array $headers        	
	 */
	public function set_response($status, $data = null, $headers = null) {
		$this->status = $status;
		$this->status_message = $this->get_status_message($this->status);
		$this->data = $data;
		$this->headers = $headers;
	}
	/**
	 * set content of https_response from response
	 * @param response $model_response        	
	 */
	public function set_from_response(response $model_response) {
		$this->set_response($model_response->status, $model_response->data, $model_response->headers);
	}
	/**
	 * set content of https_response from api_user_exception
	 * @param api_user_exception $e        	
	 */
	public function set_from_exception(api_user_exception $e) {
		$this->set_response(400, [
			'error' => $e->getMessage() 
		]);
	}
	/**
	 * determines status message by status
	 * @param integer $status        	
	 * @return NULL|string
	 */
	private function get_status_message($status) {
		return isset($this->status_messages[$status]) ? $this->status_messages[$status] : null;
	}
	/**
	 * prints the response
	 */
	public function print_response() {
		$this->send_headers();
		$this->send_response();
	}
	/**
	 * sends headers
	 */
	private function send_headers() {
		header("HTTP/1.1 {$this->status}");
		header('Content-Type: application/json');
		header('Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate');
		
		if (is_array($this->headers)) {
			foreach ( $this->headers as $header ) {
				if ($header) header($header);
			}
		}
	}
	/**
	 * sends response as json
	 */
	private function send_response() {
		echo json_encode([
			'status' => $this->status,
			'status_message' => $this->status_message,
			'data' => $this->data 
		]);
	}
}
?>