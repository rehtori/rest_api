<?php namespace rest\classes\persons;
use rest\classes\ApiUserException;

/**
 * 
 * @author janner
 *
 */
class Persons {
	public $id;
	public $name;
	public $email;
	public $birthday;
	public $external_id;
	/**
	 * sets the content of data to attributes
	 * @param array $data
	 */
	public function __construct($data = null) {
		if (isset($data['id']) && $data['id']) {
			$this->id = $data['id'];
			$this->update_from_array($data);
		}
	}
	/**
	 * updates attribute values from data
	 * @param array $data
	 * @throws api_user_exception
	 */
	public function update_from_array($data) {
		$this->name = isset($data['name']) ? $data['name'] : $this->name;
		$this->email = isset($data['email']) ? $data['email'] : $this->email;
		
		// validate birthday
		$birthday = isset($data['birthday']) ? $data['birthday'] : null;
		if ($birthday) {
			list($dd, $mm, $yyyy) = explode('.', $birthday);
			if (! checkdate($mm, $dd, $yyyy)) {
				throw new ApiUserException("Birthday validation failed");
			} else {
				$this->birthday = $birthday;
			}
		}
		
		$this->external_id = isset($data['external_id']) ? (int) $data['external_id'] : $this->external_id;
	}
}

?>