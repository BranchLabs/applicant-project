<?php

require_once('./Config.php');

class AbstractModel {
	
	protected $_table;
	protected $_pk;
	protected $_data;

	protected $_db_connection;

	private function _initDb() {
		
		if(!$this->_db_connection) {

			$dsn = sprintf('mysql:host=%s;dbname=%s', Config::DB_HOST, Config::DB_NAME);

			try {
				$this->_db_connection = new PDO($dsn, Config::DB_USER, Config::DB_PASS);

			} catch(Exception $e) {
				echo 'Connection to DB Failed: ' . $e->getMessage();
			}
		}
	}

	// Helper function to get the data type of a value
	// before inserting into the database
    private function _getValueType($value) {
        
        switch (true) {
          	case is_bool($value):
                $var_type = PDO::PARAM_BOOL;
                break;
            case is_int($value):
                $var_type = PDO::PARAM_INT;
                break;
            case is_null($value):
                $var_type = PDO::PARAM_NULL;
                break;
            default:
                $var_type = PDO::PARAM_STR;
        }

        return $var_type;
    }

    // Helper function to get the next auto increment value
    // in the primary key
	private function _getAutoIncrementPk() {
		$this->_initDb();

		$sql = sprintf('SELECT MAX(%s) FROM %s', $this->_pk, $this->_table );
		$sth = $this->_db_connection->prepare($sql);
		$sth->execute();

		$current_max_id = $sth->fetchColumn();

		return ++$current_max_id;
	}

	public function getData($key = false) {
		
		//If no key is specified, return the entire record
		if($key) {
			return $this->_data[$key];
		}
		else {
			return $this->_data;
		}

	}
	
	public function setData($arr, $value=false) {
		
		if($value) {
			// set data of one field
			$this->_data[$arr] = $value;
		}
		else if ( isset($this->_data) ) {
			// set data of multiple fields
			$this->_data = array_merge($this->_data, $arr);
		}
		else {
			// set new data
			$this->_data = $arr;
		}
		
		return $this;

	}

	// Loads a database record into the model
	public function load($id) {
		
		$this->_initDb();

		$sql = sprintf('SELECT * FROM %s WHERE %s = :pk', $this->_table, $this->_pk);
		$sth = $this->_db_connection->prepare($sql);
		$sth->bindParam(':pk', $id);
		$sth->execute();

		$this->_data = $sth->fetch(PDO::FETCH_ASSOC);

		return $this;
	}

	// Saves the model to the database
	public function save() {
		//if no id is set, the record is new
		$isNew = isset($this->_data[$this->_pk]) ? false : true;

		if($isNew) {
			$this->_insert();
		}
		else {
			$this->_update();
		}
	}

	// UPDATE a database record
	private function _update() {

		$this->_initDb();

		$fields = array();
		$types = array();

		$updated_data = $this->_data;

		//ignore the primary key
		unset($updated_data[$this->_pk]);
		
		// Prepare UPDATE statement in the form "field = ?",
		// also store the value data type to use later in the parameters
		foreach ($updated_data AS $key => $value) {
			
            $fields[] = sprintf('%s = ?', $key);
            $types[] = $this->_getValueType($value);
        }

		$fields = implode(', ', $fields);
		$sql = sprintf('UPDATE %s SET %s WHERE %s = ?', $this->_table, $fields, $this->_pk );

		$sth = $this->_db_connection->prepare($sql);
		
		$num = 0;
		foreach ($updated_data as $key => &$value) {
		    $sth->bindParam($num + 1, $value, $types[$num]);
		    $num++;
		}

		$sth->bindParam($num + 1, $this->_data[$this->_pk]);

		$sth->execute();

		return $this;
	}

	// INSERT a record into the database
	private function _insert() {
		
		$this->_initDb();

		$types = array();
		$i = 0;
        $value_params = '';

		// Prepare INSERT statement with the new primary key,
		// field names, and field value parameters
		$this->_data = array($this->_pk => $this->_getAutoIncrementPk() ) + $this->_data;

        $fields = implode(', ', array_keys($this->_data));

        foreach ($this->_data AS $key => $value) {
			
            $value_params .= ($i === 0) ? '?' : ', ?';
            $types[] = $this->_getValueType($value);

            $i++;
        }
        
		$sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $this->_table, $fields, $value_params );

		$sth = $this->_db_connection->prepare($sql);
		
		$i = 0;
		foreach ($this->_data as $key => &$value) {
		    $sth->bindParam($i + 1, $value, $types[$i]);
		    $i++;
		}

		$sth->execute();

		return $this;

	}

	// DELETE a record from the database
	public function delete($id = false) {

		$this->_initDb();

		$id = $id ? $id : $this->_data[$this->_pk];

		$sql = sprintf('DELETE FROM %s WHERE %s = :pk', $this->_table, $this->_pk);
		$sth = $this->_db_connection->prepare($sql);
		$sth->bindParam(':pk', $id);
		$sth->execute();

	}

}