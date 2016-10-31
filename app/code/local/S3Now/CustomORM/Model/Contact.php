<?php
require_once(dirname(__FILE__) . "/AbstractModel.php");
Class Contact extends AbstractModel
{
	protected $_table = "contacts";
	protected $_pk	  = "id";
}


/**
 * Hacky McHackerson, so this 'ORM' can work with Magento
 **/
class S3Now_CustomORM_Model extends Contact {
  protected function _construct(){
     $this->_init("customorm/contact");
  }
}
