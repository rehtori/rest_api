<?php namespace rest\classes;
/**
 * 
 * @author janner
 *
 */
class Database {
	public $connection = false;
	private $host = 'localhost';
	private $dbName = 'database_1';
	private $username = '';
	private $password = '';
	/**
	 * gets the PDO connection
	 * @return boolean|\PDO connection
	 */
	public function get_PDO() {
		if ($this->connection === false) {
			try {
				$this->connection = new \PDO("mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4", $this->username, $this->password);
				$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				$this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
			} catch ( PDOException $exception ) {
				echo "Connection error: " . $exception->getMessage();
			}
		}
		
		return $this->connection;
	}
}

?>