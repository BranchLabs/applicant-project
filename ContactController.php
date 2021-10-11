<?php
require_once("Contact.php");

/* 
* This file would ideally handle and route requests to the corresponding action.
*  In this case, it only outputs the info of the matching contact id.
*/
$contact_id = get_query_var( 'contact_page' );
$action = get_query_var( 'action' );

$contact = new Contact();

$contact->load( $contact_id );
?>
<h3 style="font-family:sans-serif;">
    <?php echo $contact->getData(); ?>
</h3>