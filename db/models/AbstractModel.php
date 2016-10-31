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
  private $_record = null;

  /**
   * Saves a record
   *
   * @author David Cajio
   */
  public function save() {
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

    //return Database::execute(sprintf($query, $this->_table, $this->_pk, $id)); // changed this to use PDO instead (Dave Cajio)
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

    return $this->_delete();
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
  }

} // END class AbstractModel
?>
