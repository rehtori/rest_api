<?php namespace rest;

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../../models/persons.php';
/**
 * 
 * @author janner
 *
 */
class persons_manager {
	private $db = false;
	
	/**
	 * creates new person to database
	 * @param array $data
	 * @return NULL|string contains last inserted id, if successful
	 */
	public function create_person($data) {
		$last_insert_id = null;
		$person = new persons();
		$person->update_from_array($data);
		if ($person) {
			$db = $this->get_database();
			$stmt = $db->prepare("
				INSERT INTO persons
				SET
					external_id = :external_id,
					name = :name,
					email = :email,
                    birthday = :birthday
			");
			$stmt->execute([
				':external_id' => $person->external_id,
				':name' => $person->name,
				':email' => $person->email,
				':birthday' => $this->format_date_to_db($person->birthday) 
			]);
			$last_insert_id = $db->lastInsertId();
		}
		
		return $last_insert_id;
	}
	/**
	 * formats date before saving to database
	 * @param array $date
	 * @return NULL|string contains parsed date if valid
	 */
	public function format_date_to_db($date) {
		$result = null;
		if ($date) {
			$parsed = date('Y-m-d', strtotime($date));
			if ($parsed) $result = $parsed;
		}
		
		return $result;
	}
	/**
	 * gets person by id
	 * @param string $id person id
	 * @param string $state
	 * @return boolean|\rest\persons
	 */
	public function get_person($id, $state = 'ready') {
		$db = $this->get_database();
		
		$stmt = $db->prepare("
			SELECT 
				id,
				external_id,
				name,
				email,
                DATE_FORMAT(birthday, '%d.%m.%Y') as birthday
			FROM 
				persons
			WHERE
				id = :id
                AND state = :state
		");
		$stmt->execute([
			':id' => $id,
			':state' => $state 
		]);
		
		return ($results = $stmt->fetch()) ? new persons($results) : false;
	}
	/**
	 * gets all persons or by name
	 * @param string $query_string
	 * @param string $state
	 * @return boolean|\rest\persons[] containing persons
	 */
	public function get_persons($query_string, $state = 'ready') {
		$search_array = [];
		parse_str($query_string, $search_array);
		$persons = false;
		// enable searching by name, others should not work yet
		if (isset($search_array['name']) && $search_array['name']) {
			$persons = $this->query_persons_by_name($search_array['name'], $state);
		} else {
			$persons = $this->query_persons($state);
		}
		
		return $persons;
	}
	/**
	 * gets persons from database
	 * @param string $state
	 * @return \rest\persons[]
	 */
	public function query_persons($state = 'ready') {
		$db = $this->get_database();
		$stmt = $db->prepare("
			SELECT
				id,
	    		external_id,
				name,
				email,
                DATE_FORMAT(birthday, '%d.%m.%Y') as birthday
			FROM
				persons
			WHERE
				state = :state
            ORDER BY
                name,
                id
		");
		$stmt->execute([
			':state' => $state 
		]);
		$persons = [];
		while ( $row = $stmt->fetch() ) {
			if ($row['id']) $persons[] = new persons($row);
		}
		return $persons;
	}
	/**
	 * gets persons from database by name string
	 * @param string $name name of person
	 * @param string $state
	 * @return \rest\persons[]
	 */
	public function query_persons_by_name($name, $state = 'ready') {
		$db = $this->get_database();
		$stmt = $db->prepare("
			SELECT
				id,
	    		external_id,
				name,
				email,
                DATE_FORMAT(birthday, '%d.%m.%Y') as birthday
			FROM
				persons
			WHERE
				name = :name
 				AND state = :state
            ORDER BY
                name,
                id
		");
		$stmt->execute([
			':name' => $name,
			':state' => $state 
		]);
		$persons = [];
		while ( $row = $stmt->fetch() ) {
			if ($row['id']) $persons[] = new persons($row);
		}
		return $persons;
	}
	
	/**
	 * updates persons info by id if exists
	 * @param string $id person id
	 * @param array $data
	 * @return boolean
	 */
	public function update_person($id = null, $data = []) {
		if ($id) {
			$person = $this->get_person($id);
			if ($person) {
				$person->update_from_array($data);
				
				$db = $this->get_database();
				$stmt = $db->prepare("
    				UPDATE persons
    				SET
    					external_id = :external_id,
    					name = :name,
    					email = :email,
                        birthday = :birthday
    				WHERE
    					id = :id
    			");
				$updated = $stmt->execute([
					':id' => $person->id,
					':external_id' => $person->external_id,
					':name' => $person->name,
					':email' => $person->email,
					':birthday' => $this->format_date_to_db($person->birthday) 
				]);
			}
		}
		
		return isset($updated) ? $updated : false;
	}
	/**
	 * updates state to 'trashed'
	 * @param string $id person id
	 * @return boolean
	 */
	public function delete_person($id) {
		return $this->set_state($id, 'trashed');
	}
	/**
	 * updates state to 'ready'
	 * @param string $id person id
	 * @return boolean
	 */
	public function publish_person($id) {
		return $this->set_state($id, 'ready');
	}
	/**
	 * update state of persons
	 * @param string $id person id
	 * @param unknown $state
	 * @return boolean
	 */
	public function set_state($id, $state) {
		$result = false;
		if ($id && $state) {
			$db = $this->get_database();
			$stmt = $db->prepare("
				UPDATE persons
				SET
					state = :state
				WHERE
					id = :id
			");
			$result = $stmt->execute([
				':id' => $id,
				':state' => $state 
			]);
		}
		
		return $result;
	}
	/**
	 * gets the database connection
	 * @return boolean|PDO
	 */
	public function get_database() {
		if ($this->db === false) {
			$database = new database();
			$this->db = $database->get_PDO();
		}
		
		return $this->db;
	}
}
?>