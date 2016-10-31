<?php
require_once(dirname(__FILE__) . "/AbstractModel.php");
Class Contact extends AbstractModel
{
	protected $_table = "contacts";
	protected $_pk	  = "id";
}
