<?php
require_once 'dbconfig.php';
require_once 'autoload.php';

/*

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
