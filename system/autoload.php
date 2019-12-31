<?php
include 'Loader.php';

class autoload{
    private static $loader;

    public static function getLoader(){
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register('Loader::autoload'); // 注册自动加载
    }



}

return autoload::getLoader();