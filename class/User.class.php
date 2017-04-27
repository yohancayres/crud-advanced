<?php
/*
 * This is a sample model class for using Crud-Advanced.
 * For her use, she needs to extend the Crud class.
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