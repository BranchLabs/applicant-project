<?php

require_once("./AbstractModel.php");

class Contact extends AbstractModel
{
    protected $_table   = "contacts";
    protected $_pk      = "id";
}