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

Screenshots:
Contact Tests View (index controller):
https://www.dropbox.com/s/gmc8vf3khrssl2p/Selection_043.png?dl=0

Single User View (view?id=1):
https://www.dropbox.com/s/prrnwh6nw3h5zhv/Selection_042.png?dl=0

Database Results after test:
https://www.dropbox.com/s/z04qqaqxkiyqk3q/Selection_044.png?dl=0


