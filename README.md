# BranchLabs Applicant Project

The BranchLabs applicant project involves implementing a basic ORM in PHP and then integrating it with a framework. Yes, you may be duplicating functionality already provided by your framework of choice, but that's okay.

The project generally takes folks around 4 hours.

If you run into any questions while reviewing the documentation or implementing the project, feel free to send your interviewer an email.

## Part I. Abstract Model

Subjects tested:
  * OO Concepts
  * MySQL
  * CRUD
  * Object-relational mapping

In this folder you will find
  * `Contact.php` - File with code to be used for testing
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

Using a framework of your choice, incorporate the abstract model into a simple application. Like mentioned above, even if your framework already has an ORM, try integrating this simple one with it and using it instead. Feel free to rename the classes to make them work better within your Framework.

Application guidelines:
* Must have a URL where a contact's information is visible—something like example.com/contact/view/id/[some_id]
* Must use the Contact model shown above to load the record.

If you have extra time, feel free to add extra bits of polish.

## Part III. Submission

Once you've completed the project, submit a pull request on this project with your implementation and shoot us an email back. Let your interview what you thought about the project, and any issues or questions you had while working on it. Context and communication is important on a remote team!
