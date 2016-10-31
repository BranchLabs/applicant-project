<?php
require_once("app/code/local/S3Now/CustomORM/Model/Database.php");
$db = new Database();
$db->conn();

class S3Now_CustomORM_Block_Index extends Mage_Core_Block_Template {

  /**
   * Gets the contact specified by the id param
   *
   * @return void
   * @author David Cajio
   */
  public function getViewContact() {
    $contact = Mage::getModel('customorm/contact');

    $id = Mage::app()->getRequest()->getParam("id");
    if (!$id) {
      return new Contact();
    }


    $contact = new Contact();
    return $contact->load($id);
  }
}
