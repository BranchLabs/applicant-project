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
require_once(dirname(__FILE__) . "/../Database.php");
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
    // Modified to use ON DUPLICATE KEY UPDATE
    // Especially since it makes it easier than checking for the
    // $_pk value via code, let the DB handle it
    //
    // This is acceptable because the requirements specifically state
    // composite primary keys will not need to be used and all models use
    // a single primary key
    //
    /*
    if (!$this->_record['id']) {
      // insert the record
      $this->_insert();
    } else {
      // update the record
      $this->_update();
    }
     */

    $query = "INSERT INTO `%s` (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s";
    $params = $this->_getPreppedParams();

    // since this is a simple ORM, just return the boolean value
    return Database::write(sprintf($query, $this->_table,
      implode(",", $params['keys']),
      implode(",", $this->_insertValuesPDO($params['values'])),
      implode(",", $this->_onDuplicateKeyUpdates($params['keys']))), $params['values']);

  }


  /**
   * Returns an array of ?'s based on the count of the values
   * array provided
   *
   * @return array
   * @author David Cajio
   */
  private function _insertValuesPDO($arr) {
    $size = count($arr);
    $insert_values = new SplFixedArray($size);

    for ($x=0 ; $x<$size ; $x++) {
      $insert_values[$x] = "?";
    }

    print_r($insert_values);
    return $insert_values->toArray();
  }

  /**
   * Formats SQL for on duplicate key update
   *
   * @return string
   * @author David Cajio
   */
  private function _onDuplicateKeyUpdates($keys) {
    $result = array();

    // Cycle the keys and give them SQL friendly values
    foreach ($keys as $_key) {
      $result[] = $_key . "=VALUES(" . $_key . ")";
    }

    return $result;
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

    foreach ($this->_record as $_key => $_value) {
      $params['values'][] = $_value;
      $params['keys'][] = "`" . $_key . "`";
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

    $query = "SELECT * from %s where %s=?";
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

    return $this->_delete(); // delete is not chainable since it ends the record, do not return $this
  }

  /**
   * Deletes the loaded record if one is loaded
   *
   * @author David Cajio
   */
  private function _delete() {
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
