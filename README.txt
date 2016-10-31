Most of the code for the Magento Module was auto-generated using a tool.

Custom code can mostly be found in app/code/local/S3Now/CustomORM/Model.

Including:
Contact.php
AbstractModel.php
Database.php

You will, also, need to create a config directory under the aforementioned directory with a db.json file in order
to connect to the "custom ORM" database, since this was meant to be a custom ORM outside of Magento
we could not use Magento's local.xml to connect.


Here is an example:
{
  "host": "172.17.0.2",
  "db": "branchlabs",
  "username": "branchlabs",
  "password": "branchlabs"
}


The frontend page used for Magento was simply the contact_tests.php file provided by the original test.
