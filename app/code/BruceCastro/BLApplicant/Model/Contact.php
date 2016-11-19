<?php

namespace BruceCastro\BLApplicant\Model;

require_once(dirname(__FILE__) . "/ResourceModel/AbstractModel.php");

class Contact extends ResourceModel\AbstractModel {
	protected $_table = "contacts";
	protected $_pk	  = "id";

}