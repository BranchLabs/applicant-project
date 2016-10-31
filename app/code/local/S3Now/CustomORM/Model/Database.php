<?php

/**
 * Database class handles, all basic DB functionality, including
 * connections, queries, etc
 * @package Database
 * @author David Cajio
 *
 * TODO: Implement Log class and functionality (bells and whistles)
 */
class Database {
  protected static $_db; // Database connection


  /**
   * Gets the configuration from the config file
   *
   * @return void
   * @author David Cajio
   */
  protected function _getConfig() {
    try {
      $config_contents = file_get_contents(dirname(__FILE__) . "/config/db.json");
    } catch (Exception $e) {
      // Normally we'd tie into our Framework and do something clever here,
      // but no framework so let's just throw the error
      throw $e;
    }

    return json_decode($config_contents);
  }

  /**
   * Gets the DB variable
   *
   * @return object
   * @author David Cajio
   */
  public static function getDB() {
    return Database::$_db;
  }

  /**
   * Prepares a SQL query using PDO instead of using
   * sprintf/manual variable replacement
   *
   * @return string
   * @author David Cajio
   */
  public static function prepare($query) {
    // TODO:
    // In a REAL ORM this should be using transactions
    $db = Database::getDB();
    $stmt = $db->prepare($query);
    return $stmt;
  }

  /**
   * Executes a read query against the database
   *
   * @return mixed
   * @author David Cajio
   */
  public static function read($query, $params = array()) {
    $stmt = Database::prepare($query);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC); // based on requirements we are only ever fetching a single row
  }


  /**
   * Writes to the database
   *
   * @return boolean
   * @author David Cajio
   */
  public function write($query, $params = array()) {
    $stmt = Database::prepare($query);
    $result = $stmt->execute($params);

    // we can check the value of $result and do some error handling
    // but that is beyond the scope of this excercise, just return the value
    //
    // Note: Using lastInsertId isn't a good solution, but this is an 'excercise' not a real
    // ORM and there is a time-crunch here so in this case, it's "good enough"
    return array("result" => $result, "id" => Database::getDB()->lastInsertId());
  }

  /**
   * Connects to the DB
   *
   * @return void
   * @author David Cajio
   */
  public function conn() {
    try {
      $config = $this->_getConfig();
      $conn_string = sprintf('mysql:host=%s;dbname=%s', $config->host, $config->db);
      $db = new PDO($conn_string, $config->username, $config->password);
    } catch (PDOException $e) {
      // Failed to connect, handle this in Framework
      throw $e;
    } catch (Exception $e) {
      // Normally we'd tie into our Framework and do something clever here,
      // but no framework so let's just throw the error
      throw $e;
    }

    if (!$db) { // be absolutely sure we have connected
      throw new Exception("Database connection could not be established");
    }

    Database::$_db = $db;
  }
} // END class Database
?>
