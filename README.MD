# Crud-Advanced

### This class is a Crud with advanced methods. Provides agility and productivity in development.

All the methods and a description about them will be listed below. All class Crud.class.php is documented.

## Installation 
### Install Manually
You can install CrudAdanved manually
```php
require_once "src/CrudAdvanced/DataBase.php";
require_once "src/CrudAdvanced/Val.php";
require_once "src/CrudAdvanced/Crud.php";

use CrudAdvanced\DataBase;
use CrudAdvanced\Val;
use CrudAdvanced\Crud;
```

### Install with Composer
You can install CrudAdanved with composer
With command:
```bash
composer require yohancayres/crud-advanced
```
or adding in composer.json
```json 
{
    "require": {
        "yohancayres/crud-advanced": "dev-master"
    }
}
```

## Available Methods

### Generic Methods
- _set($key, $value) - Set or change the value of an attribute;
- _get($key) - Get the value of an attribute;
- loadData(array $data) - Loads data from an array to object attributes;
- requiredParam(array $params) - Checks whether the required attribute exists;
- getParamArray(array $params) - Get an array of object attributes;

### Databases Methods
- [dbInsert()](#insert-new-user-with-dbinsert) - Inserts the attributes of the object in the database;
- [dbUpdateAll()](#update-some-row-with-dbupdateall-or-dbupdateatts) - Refreshes all attributes in the database;
- [dbUpdateAtts($atts)](#update-some-row-with-dbupdateall-or-dbupdateatts) - Updates specific attributes in the database;
- [dbUpdate($attr, $newvalue)](#update-directly) - Defines and updates the value of an attribute in the database;
- dbUpdateIncrease($attr, $amount) - Updates an attribute by incrementing its value (this value can be negative);
- dbSearch($attr, $data) - Searches the database;
- dbRemove() - Removes a record from the database;
- dbCheckExists($attr) - Checks whether a record already exists;
- dbLoadData($values) - Loads all data from the database;
- [dbLoadDataBy($attr, $values)](#dbloaddataby) - Loads all database data using an attribute defined by the first parameter;
- fetchById($id, $values) - Get database record;
- fetchRandom($values) - Gets a random record of the database;
- fetchAll($where, $loadAtts) - Get all records from the table;

### Relationship
- hasOne($className, $thisAttName, $classAttName, $loadAtts)- Creates a one-to-one relationship;
- hasMany($className, $thisAttName, $classAttName, $loadAtts) - Creates a one-to-many relationship;


## Examples

#### Connect to the Database
First of all, the application needs information to connect to the database
```php
use CrudAdvanced\DataBase;

DataBase::configure('127.0.0.1', 'root', 'password', 'dbteste');
```

#### Using Crud
To create a class and use the Crud Methods, you need to extends Crud to your class.
```php
use CrudAdvanced\Val;
use CrudAdvanced\Crud;

class User extends Crud {

	/* Database table definition */
	public $table = "users"; // Define Table name
	public $tableCols = ['name', 'email', 'password']; // Define table cols

	/*
	 * Other class methods and attributes can be defined freely.
	 */
}
```


#### Insert new user with dbInsert()
This method will insert all the attributes to the database. Be aware of the name of the attributes, they must be exactly the same as the fields in the database.
```php
$user = new User([
	'name' => 'Yohan Cayres',
	'email' => 'yohan.cayres@mail.com',
	'password' => md5('somepassword')
]); // Create new object type User

$user->dbInsert(); // Insert at the Database
```

#### dbLoadDataBy()
This method will load attributes from database, making the search for the defined attribute.
```php
$user->dbLoadDataBy('email', 'id,name,password'); // Loads a database attribute by email.
echo $user->_get('id');
```

#### Update some row with dbUpdateAll() or dbUpdateAtts()
This method needs the id attribute, you can get it through dbLoadDataBy();
```php
$user->_set('email', 'newMail@mail.com'); // This will set a new email

$user->dbUpdateAtts('email'); // Only the attribute 'email' will be updated
$user->dbUpdateAll(); // All the attributes will be updated
```

#### Update directly
This method needs the id attribute, you can get it through dbLoadDataBy();
```php
$user->dbUpdate('email', 'newMail@mail.com'); // Set and update directly.
```

## Hosting Options
[Web Hosting](https://hostmf.com.br)  [VPS Windows](https://hostmf.com.br/vps-windows)  [VPS Linux](https://hostmf.com.br/vps-linux)
