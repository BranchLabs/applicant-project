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
class AbstractModel {

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
   * @author David Cajio
   */
  public function load($id) {
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
