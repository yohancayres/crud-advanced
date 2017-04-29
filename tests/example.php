<?php
require_once '../vendor/autoload.php';

use CrudAdvanced\DataBase;
use CrudAdvanced\Val;
use CrudAdvanced\Crud;

/**
 * This is an example where a class created extends the class Crud
 *  The crud class must be extended to the class that will make use of it.
 */
class User extends Crud {
	
	/*
	 * Define the $table atribute and the $tableCols atribute.
	 * These attributes define the name of the table, and the fields of the table, respectively.
	 */
	
	public $table = "users";
	public $tableCols = ['name', 'email', 'password'];


	/*
	 * Other class methods and attributes can be defined freely.
	 */
}




/* Open MySQL Connection with the DataBase class */
DataBase::configure('127.0.0.1', 'root', '', 'dbteste');





/* Insert example */
$user = new User(['name' => 'Yohan Lopes', 'email' => 'yohan@mail.com', 'password'=> md5('somepassword')]);

if(!$user->dbCheckExists('email')){
	$user->dbInsert();
}

/* Get data by email */
$user->dbLoadDataBy('email', 'id');
print_r($user);

/* Change object atribute */
$user->_set('email', 'yohan@newmail.com');
if(!$user->dbCheckExists('email')){
	$user->dbUpdateAll();
}

print_r($user);
