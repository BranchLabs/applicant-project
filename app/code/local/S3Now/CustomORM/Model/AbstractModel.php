<?php
/**
 * Abstract Model class
 *
 * Is the Base model for all other specific models.
 * For example a Contact model would extend this model
 * to ensure all models properly handle DB calls.
 *
 * @package Database
 * @author David Cajio
 */
require_once(dirname(__FILE__) . "/Database.php");
class AbstractModel {

  /**
   * Blank record, nothing loaded yet
   *
   * @var array
   */
  private $_record = array();

  /**
   * Saves a record
   *
   * @author David Cajio
   */
  public function save() {
    // reimplemented to make things easier from an observation/user practice
    // so that an exact query can be seen and the result observed
    //
    // Also, the test did specify: "Should run an UPDATE query", something
    // that was originally missed
    if (!array_key_exists($this->_pk, $this->_record)) {
      // insert the record
      return $this->_insert();
    } else {
      // update the record
      return $this->_update();
    }
  }

  /**
   * Runs an insert for a new record
   *
   * @return boolean
   * @author David Cajio
   */
  public function _insert() {
    $query = "INSERT INTO `%s` (%s) VALUES (%s)";
    $params = $this->_getPreppedParams();

    $parsed = sprintf($query, $this->_table,
      implode(",", $params['keys']),
      implode(",", $params['tokens']));

    // since this is a simple ORM, just return the boolean value
    $result = Database::write($parsed, $params['writeable']);

    if ($result['result'] && $result['id'] > 0) {
      $this->_record[$this->_pk] = $result['id'];
    }

    return $result['result'];
  }

  /**
   * Updates a record in the DB
   *
   * @return boolean
   * @author David Cajio
   */
  public function _update() {
    $query = "UPDATE `%s` SET %s WHERE `%s`=%d";
    $params = $this->_getPreppedParams();

    $parsed = sprintf($query, $this->_table,
      implode(",", $params['update_tokens']),
      $this->_pk,
      $this->_record[$this->_pk]
    );

    // since this is a simple ORM, just return the boolean value
    $result = Database::write($parsed, $params['writeable']);

    return $result['result'];
  }

  /**
   * Gets an array of values needed to formulate a proper insert
   *
   * @return mixed
   * @author David Cajio
   */
  private function _getPreppedParams() {
    $params = array();

    // First we need to just the values
    $params['values'] = array();

    // Second we need just the keys
    $params['keys'] = array();

    // Thirdly setup tokens for PDO
    $params['tokens'] = array();

    // if we need to run an update
    $params['update_tokens'] = array();

    // What is allowed to be written
    $params['writeable'] = array();

    // setup an array that we can access to create SQL statements
    //
    // this could be much cleaner, but for our simplistic ORM it serves it's purpose
    foreach ($this->_record as $_key => $_value) {
      // if ($_key !== $this->_pk) { // never update the primary key
      // Apparently, according to the test this is allowed, though this is highly
      // discouraged by all other ORMS I know of as it can lead to accidentally overwriting
      // the wrong record
      //
      // Since the exercise wants it however, we'll add it back in
        $params['values'][] = $_value;
        $params['tokens'][] = ":" . $_key;
        $params['keys'][] = "`" . $_key . "`";
        $params['update_tokens'][] = "`" . $_key . "` = :" . $_key;
        $params['writeable'][$_key] = $_value;
      // }
    }

    return $params;
  }


  /**
   * Loads a document by ID
   *
   * Since this isn't a full fledged ORM, we are going to assume
   * that IDs are always numeric
   *
   * Based on the project requirements, we can, also, assume (unsafely) that ID is
   * always required and load cannot be called without since functions such as delete()
   * have a default id value, whereas the test/use-cases provided and the load function
   * all indicate that a load based on a query or recordset is not possible
   *
   * @author David Cajio
   */
  public function load($id) {
    if (!isset($id)) throw new Exception("ID is required to fetch a record"); // safe assumption

    // First clear our record, even on a fail we don't want an old record sitting around
    $this->_record = array();

    $query = "SELECT * FROM `%s` WHERE `%s`=?";
    $record = Database::read(sprintf($query, $this->_table, $this->_pk), array($id));
    if ($record) {
      $this->_record = $record;
    }

    return $this; // because we want to be able to chain calls
  }

  /**
   * Deletes a document by ID, or the document
   * that is already loaded
   *
   * @author David Cajio
   */
  public function delete($id = false) {
    // If we have an ID, then delete that record
    if ($id) return $this->_deleteById($id);

    $result = $this->_delete(); // delete is not chainable since it ends the record, do not return $this

    // we could check result, see if the delete executed correctly, but this excercise is
    // simple, so we won't go down that path
    return $result;
  }

  /**
   * Deletes the loaded record if one is loaded
   *
   * @author David Cajio
   */
  private function _delete() {
    if (!array_key_exists($this->_pk, $this->_record)) {
      throw new Exception("Cannot delete a record without an id");
    }

    $query = "DELETE FROM `%s` WHERE `%s`= %d";
    $parsed = sprintf($query, $this->_table, $this->_pk, $this->_record['id']);
    $result = Database::write($parsed);

    $this->_record = array(); // reset our record, whether fail or not
    return $result['result'];
  }

  /**
   * Deletes a record by ID
   *
   * @return boolean
   * @author David Cajio
   */
  private function _deleteById($id) {
    $this->_record = array();
    $this->_record[$this->_pk] = $id;
    return $this->_delete();
  }

  /**
   * Returns a single data element or all of the data
   * elements depending on if key is supplied
   *
   * @author David Cajio
   */
  public function getData($key = false) {
    if ($key) return $this->_getDataByKey($key);
    return $this->_getRecordData();
  }

  /**
   * Gets data by a given key
   *
   * @return mixed
   * @author David Cajio
   */
  private function _getDataByKey($key) {
    return $this->_record[$key];
  }

  /**
   * Returns the entire record or null on a blank
   *
   * @return array
   * @author David Cajio
   */
  private function _getRecordData() {
    return $this->_record;
  }

  /**
   * Sets/Clears a data element of the current record
   *
   * @author David Cajio
   */
  public function setData($arr, $value = false) {
    switch (gettype($arr)) {
      case "array":
        return $this->_setDataArr($arr); // value should be ignored
        break;

      case "string":
        return $this->_setDataKey($arr, $value);
        break;

      default:
        throw new Exception("Invalid data supplied for setData, arr must be an array of values or a string key");
    }
  }

  /**
   * Sets values based on a key=>value array
   *
   * @return this
   * @author David Cajio
   */
  private function _setDataArr($arr) {
    // we can only have gotten here if arr == array, so no need to check if it is an array
    foreach ($arr as $_key => $_val) {
      // we are using PDO so we can safely let PDO deal with mysql_escape
      $this->_record[$_key] = $_val;
    }

    return $this;
  }


  /**
   * Sets a single value in the record based on key => val
   *
   * @return this
   * @author David Cajio
   */
  private function _setDataKey($key, $val) {
    $this->_record[$key] = $val;

    return $this;
  }

} // END class AbstractModel
?>
