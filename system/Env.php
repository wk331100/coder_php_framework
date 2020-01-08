<?php

namespace System;

class Env{

    static $variables;

    public static function get($key, $default = null){
        if(isset(self::$variables[$key])){
            return self::$variables[$key];
        }
        return $default;
    }

    public static function set($vars = []){
        if(!empty($vars)){
            self::$variables = $vars;
        }
    }


}