<?php
/**
 * This class is a Crud with advanced methods. Provides agility and productivity in development.
 *
 * @author Yohan Cayres <yohan.cayres@hotmail.com> 
 * 
 * @link https://github.com/yohancayres/crud-advanced/
 * 
 * @version 1.0
 */
namespace CrudAdvanced;

abstract class Crud extends DataBase {

    public $table, $tableCols;

    /**
     * This is the default constructor
     * This method can receive an array containing the attribute definition.
     * @param array|array $objectData Array containing the attribute definition.
     * @return type
     */

    public function __construct(array $objectData = array()) {
        if (count($objectData) > 0) {
            foreach ($objectData as $data => $value) {
                if (is_array($value)) {

                    if (count($value) == 2) {
                        $this->_set($data, $value[0], $value[1]);
                    } elseif (count($value) == 3) {
                        $this->_set($data, $value[0], $value[1], $value[2]);
                    } else {
                        throw new Exception("Definição incorreta para array.");
                    }
                } else {
                    $this->_set($data, $value);
                }
            }
        }
    }


    /**
     * Inserts the object into the database.
     * @return type
     */

    public function dbInsert() {
        if (isset($this->tableCols)) {
            $rowKey = array();

            foreach ($this->tableCols as $row) {
                $rowKey[] = $row;
                $executeArray[":" . $row] = $this->$row;
            }

            $stmt = DataBase::prepare("INSERT INTO " . $this->table . " (" . implode(",", $rowKey) . ") VALUES (" . implode(",", array_keys($executeArray)) . ")");
            $stmt->execute($executeArray);
        }
        return $this;
    }


    /**
     * Apply the update of all attributes of the object in the database.
     * @return type
     */

    public function dbUpdateAll() {
        if (isset($this->tableCols) && isset($this->id)) {
            $rowKey = array();

            foreach ($this->tableCols as $row) {
                $executeArray[":id"] = $this->id;

                if (isset($this->$row)) {
                    $rowKey[] = $row . " = :" . $row;
                    $executeArray[":" . $row] = $this->$row;
                }
            }

            $stmt = DataBase::prepare("UPDATE " . $this->table . " SET " . implode(",", $rowKey) . " WHERE id = :id");
            $stmt->execute($executeArray);
        }

        return $this;
    }


    /**
     * Applies the update of specified attributes of the object to the database.
     * @param type $atts Specifies the attributes that will be updated 
     * @return type
     */

    public function dbUpdateRows($atts) {
        $rows = explode(",", $atts);

        if (isset($rows) && isset($this->id)) {
            $rowKey = array();

            foreach ($rows as $row) {
                $executeArray[":id"] = $this->id;
                if (isset($this->$row)) {
                    $rowKey[] = $row . " = :" . $row;
                    $executeArray[":" . $row] = $this->$row;
                }
            }

            $stmt = DataBase::prepare("UPDATE " . $this->table . " SET " . implode(",", $rowKey) . " WHERE id = :id");
            $stmt->execute($executeArray);
        }
    }

   
    /**
     * Creates a one-to-one relationship.
     * example: $this->hasOne('City', 'city_id', 'id', '*');
     * @param type $className Reference class name
     * @param type $thisAttName Foreign key attribute of this object
     * @param type|string $classAttName Referenced class primary key
     * @param type|string $loadAtts Attributes that will be loaded
     * @return type
     */

    public function hasOne($className, $thisAttName, $classAttName = 'id', $loadAtts = "*") {
        if (isset($this->$thisAttName)) {
            if (!is_object(@$this->$className)) {
                $c = new $className([$classAttName => $this->$thisAttName]);
                $c->dbLoadData($loadAtts);
                $this->$className = $c;
            }
            return $this->$className;
        } else {
            throw new Exception("HasOne: Atributo \$this->" . $thisAttName . " não definido.");
        }
    }


    /**
     * Creates a one-to-many relationship.
     * example: $this->hasMany('Contact', 'id', 'user_id', '*');
     * @param type $className Reference class name
     * @param type $thisAttName Primary key of this object
     * @param type $classAttName Foreign key in referenced class
     * @param type|string $loadAtts Attributes that will be loaded
     * @return type
     */

    public function hasMany($className, $thisAttName, $classAttName, $loadAtts = "*") {
        if (isset($this->$thisAttName)) {
            $rdata = array();
            $c = new $className([$classAttName => $this->$thisAttName]);
            foreach ($c->FetchAll([$classAttName => $this->$thisAttName], $loadAtts) as $item) {
                $rdata[] = new $className($item);
            }
            $this->$className = $rdata;
            return $this->$className;
        } else {
            throw new Exception("HasMany: Atributo \$this-" . $thisAttName . " não definido.");
        }
    }


    /**
     * Fetch a row by attribute id
     * @param type $id Registry ID
     * @param type|string $values Columns that will be selected
     * @return type
     */

    public function fetchById($id, $values = "*") {
        $stmt = DataBase::prepare("SELECT " . $values . " FROM " . $this->table . " WHERE id = :id ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }


    /**
     * Fetch a random row in this table
     * @param type|string $values Columns that will be selected
     * @return type
     */

    public function fetchRandom($values = "*") {
        $stmt = DataBase::prepare("SELECT " . $values . " FROM " . $this->table . " ORDER BY rand() LIMIT 1");
        $stmt->execute();

        return $stmt->fetch();
    }


    /**
     * Fetch one or more rows, you can specify the condition by specifying which field to fetch.
     * @param type|null $where Attribute Name
     * @param type|string $loadAtts Columns that will be selected
     * @return type
     */

    public function fetchAll($where = null, $loadAtts = "*") {
        if (is_array($where)) {
            $column = key($where);
            $value = $where[$column];
            $stmt = DataBase::prepare("SELECT " . $loadAtts . " FROM " . $this->table . " WHERE " . $column . " = :value ORDER BY id DESC");
            $stmt->bindParam(':value', $value);
        } else {
            $stmt = DataBase::prepare("SELECT " . $loadAtts . " FROM " . $this->table . " ORDER BY id DESC");
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }


    /**
     * Set a attribute values, you can specify a validation type and a maximum size.
     * @param type $key Attribute name
     * @param type $value Attribute value
     * @param type|null $validation Type of validation (optional)
     * @param type|int $maxsize Maxsize (optional)
     * @return type
     */

    public function _set($key, $value, $validation = NULL, $maxsize = 255) {

        if ($validation == "array" || @strlen($value) <= $maxsize) {
            if (@strlen($value) > 0) {
                switch ($validation) {

                    case 'email': Val::isEmail($value);
                        break;

                    case 'int': Val::isInt($value);
                        break;

                    case 'float': Val::isFloat($value);
                        break;

                    case 'ip': Val::isIp($value);
                        break;

                    case 'string': Val::isString($value);
                        break;

                    case 'stringtxt': Val:isString(strip_tags($value));
                        break;

                    case 'password': $value = Val::passHash($value);
                        break;

                    case 'username': Val::isUsername($value);
                        break;
                }
            }

            $this->{$key} = $value;
        } else {
            throw new Exception('O campo ' . $key . ' permite até ' . $maxsize . ' caracteres.');
        }
    }


    /**
     * Get a attribute value
     * @param type $key Attribute Name
     * @return type
     */

    public function _get($key) {
        return $this->{$key};
    }


    /**
     * Get a attribute value
     * @param array $params Attribute Name Array 
     * @return type
     */

    public function requiredParam(array $params) {
        foreach ($params as $param) {
            if (!isset($this->{$param})) {
                return false;
            }
        }
        return true;
    }


    /**
     * Loads data from the database to the object, You can specify which fields will be loaded. 
     * This method uses the ID attribute as a reference.
     * @param type|string $values Columns that will be selected
     * @return type
     */

    public function dbLoadData($values = '*') {
        if (isset($this->id)) {
            $stmt = DataBase::prepare("SELECT " . $values . " FROM " . $this->table . " WHERE id = :id ");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $this->LoadData($stmt->fetch(PDO::FETCH_ASSOC));
            } else {
                throw new Exception("O id especificado não corresponde a nenhum registro.");
            }
        }
        return $this;
    }


    /**
     * Loads data from the database to the object, You can specify which fields will be loaded. 
     * This method uses the attribute specified in the first parameter as a reference.
     * @param type|string $attr Attribute Name for the where clause
     * @param type|string $values Columns that will be selected
     * @return type
     */

    public function dbLoadDataBy($attr = "id", $values = "*") {
        if (isset($this->$attr)) {
            $stmt = DataBase::prepare("SELECT " . $values . " FROM " . $this->table . " WHERE " . $attr . " = :attr ");
            $stmt->bindParam(':attr', $this->$attr);
            $stmt->execute();
            if ($stmt->rowCount() != 0) {
                $this->LoadData($stmt->fetch());
            } else {
                throw new Exception("O id especificado não corresponde a nenhum registro.");
            }
        }
        return $this;
    }


    /**
     * Load data from array to Object attributes
     * @param array $data  Attribute Definition Array
     * @return type
     */

    public function LoadData(array $data) {
        foreach ($data as $key => $value) {
            $this->_set($key, $value);
        }
        return $this;
    }


    /**
     * Remove object from the database, using the id attribute as reference.
     * @return type
     */

    public function dbRemove() {
        if (isset($this->id)) {
            $stmt = DataBase::prepare("DELETE FROM " . $this->table . " WHERE id = :id");
            $stmt->bindParam(':id', $this->id);
            return $stmt->execute();
        } else {
            throw new Exception('Não é possível deletar um usuário sem seu ID.');
        }
        return $this;
    }


    /**
     * Checks whether the record already exists in the database, by the defined attribute.
     * @param type|string $attr Attribute Name
     * @return type
     */

    public function dbCheckExists($attr = 'id') {
        $stmt = DataBase::prepare("SELECT id FROM " . $this->table . " WHERE " . $attr . " = :attr");
        $stmt->bindParam(':attr', $this->$attr);
        $stmt->execute();
        return $stmt->rowCount();
    }


    /**
     * Updates an attribute in the registry
     * @param type $attr Attribute Name
     * @param type $newvalue New Value
     * @return type
     */

    public function dbUpdate($attr, $newvalue) {
        if (isset($this->id)) {
            $stmt = DataBase::prepare("UPDATE  " . $this->table . " SET " . $attr . " = :value WHERE id = :id");
            $stmt->bindParam(':value', $newvalue);
            $stmt->bindParam(':id', $this->id);
            return $stmt->execute();
        } else {
            throw new Exception('Ocorreu um erro, o ID não está presete.');
        }
        return $this;
    }


    /**
     * Updates a record by incrementing an attribute.
     * @param type $attr Attribute Name
     * @param type $amount Amount of Increase (can be negative)
     * @return type
     */

    public function dbUpdateIncrease($attr, $amount) {
        if (isset($this->id)) {
            $stmt = DataBase::prepare("UPDATE  " . $this->table . " SET " . $attr . " = " . $attr . " + :value WHERE id = :id");
            $stmt->bindParam(':value', $amount, PDO::PARAM_INT);
            $stmt->bindParam(':id', $this->id);
            return $stmt->execute();
        } else {
            throw new Exception('Ocorreu um erro, o ID não está presete.');
        }
        return $this;
    }


    /**
     * Searches for one or more records using the LIKE command
     * @param type $attr Atribute Name
     * @param type|string $data Columns that will be selected 
     * @return type
     */

    public function dbSearch($attr, $data = "*") {
        $stmt = DataBase::prepare("SELECT " . $data . " FROM " . $this->table . " WHERE " . $attr . " LIKE :value");
        $q = "%" . $this->$attr . "%";
        $stmt->bindParam(':value', $q, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll();
    }

   
    /**
     * Returns an array of object attributes
     * @param array|array $params Attribute Names Array
     * @return type
     */

    public function getParamArray(array $params = array()) {
        if (count($params) == 0) {
            $arrayData = $this->tableCols;
        } else {
            $arrayData = $params;
        }
        foreach ($arrayData as $param) {
            if (isset($this->$param)) {
                $rarray[$param] = $this->$param;
            }
        }

        return $rarray;
    }

}
