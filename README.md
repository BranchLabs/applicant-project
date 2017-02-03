# BranchLabs Applicant Project

The BranchLabs applicant project involves implementing a basic ORM in PHP and then integrating it with the framework of your choice.

## Part I. Abstract Model

Subjects tested:
  * OO Concepts
  * MySQL
  * CRUD
  * Object-relational mapping

In this folder you will find
  * `Contact.php` - Class to be used for testing
  * `contacts.sql` - Test data
  * `contact_test.php` - Unit tests

There is a class in `Contact.php` that extends a parent class called `AbstractModel`. You will need to build this abstract class.

To develop `AbstractModel`, you will need to:
* Import `contacts.sql` into a MySQL database
* Write `AbstractModel.php`
* Confirm `contacts_test.php` runs correctly.

* REQUIRED METHODS
  * `public function save()`
  * `public function load($id)`
  * `public function delete($id=false)`
  * `public function getData($key=false)`
  * `public function setData($arr, $value=false)`
  
You will need to make database calls in these methods. Please use the PHP MySQL adapter of your choice.

**NOTE**: You do not need to make `AbstractModel` work with composite keys. Assume all models extending from this table use a single primary key.

## Part II. Framework

Subjects tested:
* MVC
* Framework Exposure

Using the framework of your choice, incorporate the abstract model into a simple application.

Application guidelines:
* Must have a URL where a contact's information is visible—something like example.com/contact/view?id=[some_id]
* Must use the Contact model shown above to load the record.

If you have extra time, feel free to add extra bits of polish.


# Important

## Installation

Rename the Config.php.dist file to Config.php and supply your correct database credentials

## Testing out the MVC

1. Rename the yii2-mvc/db.php.dist file to yii2-mvc/db.php and supply your correct database credentials

2. From the yii2-mvc root directory, run the following commands:
    
    $ php yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations
    $ php yii migrate

3. Start web server:

    $ php yii serve
