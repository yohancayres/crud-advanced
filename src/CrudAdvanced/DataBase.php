<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataBase
 *
 * @author Yohan
 */

namespace CrudAdvanced;

use PDO;

class DataBase {

    //put your code here
    private static $connection = null;
    private static $host;
    private static $user;
    private static $pass;
    private static $base;


    public static function configure($dbHost = DBHOST, $dbUser = DBUSER, $dbPass = DBPASS, $dbName = DBNAME){
        self::$host = $dbHost;
        self::$user = $dbUser;
        self::$pass = $dbPass;
        self::$base = $dbName;
    }

    public static function connect() {

        //if (self::$connection == null) {

            self::$connection = new PDO("mysql:host=" . self::$host . ";" . "dbname=" . self::$base, self::$user, self::$pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       // }

        return self::$connection;
    }

    public static function prepare($sql) {
        return self::connect()->prepare($sql);
    }

}
