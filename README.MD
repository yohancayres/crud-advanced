# Crud-Advanced

### This class is a Crud with advanced methods. Provides agility and productivity in development.

All the methods and a description about them will be listed below. All class Crud.class.php is documented.

## Installation 
### Install Manually
You can install CrudAdanved manually
```php
require_once "src/CrudAdvanced/DataBase.php"
require_once "src/CrudAdvanced/Val.php"
require_once "src/CrudAdvanced/Crud.php"

use CrudAdvanced\DataBase;
use CrudAdvanced\Val;
use CrudAdvanced\Crud;
```

### Install with Composer
You can install CrudAdanved with composer
With command:
```php
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
- dbInsert() - Inserts the attributes of the object in the database;
- dbUpdateAll() - Refreshes all attributes in the database;
- dbUpdateRows ($atts) - Updates specific attributes in the database;
- dbUpdate($attr, $newvalue) - Defines and updates the value of an attribute in the database;
- dbUpdateIncrease($attr, $amount) - Updates an attribute by incrementing its value (this value can be negative);
- dbSearch($attr, $data) - Searches the database;
- dbRemove() - Removes a record from the database;
- dbCheckExists($attr) - Checks whether a record already exists;
- dbLoadData($values) - Loads all data from the database;
- dbLoadDataBy($attr, $values) - Loads all database data using an attribute defined by the first parameter;
- fetchById($id, $values) - Get database record;
- fetchRandom($values) - Gets a random record of the database;
- fetchAll($where, $loadAtts) - Get all records from the table;

### Relationship
- hasOne($className, $thisAttName, $classAttName, $loadAtts)- Creates a one-to-one relationship;
- hasMany($className, $thisAttName, $classAttName, $loadAtts) - Creates a one-to-many relationship;
