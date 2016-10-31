<?php
ini_set("display_errors", true);
/**
 * Bootstrap and connect to our database
 **/
require_once("../db/Database.php");
$db = new Database();
$db->conn();

require_once("../db/models/AbstractModel.php");
require_once("../db/models/Contact.php");

$contact = new Contact();

$contact->load(1);
print_r($contact->getData()); 
// Should print:
// id => 1,
// name => Alan Turing,
// email => alan@turing.com
echo '<br/><br/>';
echo $contact->getData('name');
// Should print:
// Alan Turing

$contact->setData('name', 'Donald Knuth')->save(); // Should run an UPDATE query
echo '<br/><br/>';
print_r($contact->load(1)->getData());
// Should print
// id => 1,
// name => Donald Knuth,
// email => alan@turing.com

$contact->setData(array(
"id" => 1,
"name" => "Grace Hopper",
"email" => "grace@hopper.com"
))->save();
echo '<br/><br/>';
print_r($contact->load(1)->getData());
// Should print
// id => 1,
// name => Grace Hopper,
// email => grace@hopper.com

$newContact = new Contact();
$newContact->setData(array(
    "name" => "Alonzo Church",
    "email" => "alonzo@church.com"
));

$newContact->save(); // Should run an INSERT query as there is no predefined id
echo '<br/><br/>';
print_r($newContact->getData());
// Should print
// id => ? some auto increment number,
// name => Alonzo Church,
// email => alonzo@church.com

$newContact->delete(); // Should delete Mr. Church from the database
