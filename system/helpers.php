<?php

use System\Env;

if (! function_exists('env')) {
    function env($key, $default = null)
    {
        return Env::get($key, $default);
    }
}

if (!function_exists('dd')) {
    function dd($vars)
    {
        echo "<pre>";print_r($vars);echo "<pre>";
        die();
    }
}




