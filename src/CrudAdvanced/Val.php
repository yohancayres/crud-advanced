<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Validations
 *
 * @author Yohan
 */

namespace CrudAdvanced;

class Val {

    public static function isEmail($str) {
        if (filter_var($str, FILTER_VALIDATE_EMAIL) == false) {
            throw new Exception("Formato de email inválido");
        } else {
            return true;
        }
    }

    public static function isIp($str) {
        if (filter_var($str, FILTER_VALIDATE_IP) == false) {
            throw new Exception("Formato de IP inválido");
        } else {
            return true;
        }
    }

    public static function isInt($str) {
        if (is_numeric($str) == false) {
            throw new Exception("Formato de número inválido");
        } else {
            return true;
        }
    }

    public static function isFloat($str) {
        if (filter_var($str, FILTER_VALIDATE_FLOAT) == false) {
            throw new Exception("Formato de número inválido");
        } else {
            return true;
        }
    }

    public static function isUrl($str) {
        if (filter_var($str, FILTER_VALIDATE_URL) == false) {
            throw new Exception("Formato de URL inválido");
        } else {
            return true;
        }
    }

    public static function isString($str) {
        if (!preg_match("/^[a-zA-Z0-9\s- ]*$/",$str)) {
            throw new Exception("Formato de String inválido");
        } else {
            return true;
        }
    }
    
    public static function passHash($str){
        if($str == hash('md5', $str.SECRETKEY)){
            return $str;
        } else {
            return hash('md5', $str.SECRETKEY);
        }
    }
    
    public static function isUsername($str) {
        if (!preg_match("/^[a-zA-Z0-9]*$/",$str)) {
            throw new Exception("Formato de Usuário inválido");
        } else {
            return true;
        }
    }

}
