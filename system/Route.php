<?php

namespace System;
use Closure;

class Route{
    const METHOD_GET = 'get';
    const METHOD_POST = 'post';

    public static $routes;
    public static $prefix;
    public static $prefixEnabled = false;
    public static $routeMiddleware = [];

    public static function group($routePath, array $routeArray = []){
        if(is_object($routePath) && ($routePath instanceof Closure)){
            $routePath();
        }
        if(!empty($routeArray)){
            foreach ($routeArray as $route){
                $routeFile = $routePath . DIRECTORY_SEPARATOR . $route . '.php';
                require_once $routeFile;
            }
        }
    }

    public static function addRouter($url, $path, $method = []){
        $pathArr = explode('@', $path);

        if(self::$prefixEnabled){
            $url = DIRECTORY_SEPARATOR . self::$prefix . DIRECTORY_SEPARATOR .  $url;
        }

        self::$routes[$url] = [
            'controller' => $pathArr['0'],
            'action'     => $pathArr['1'],
            'method'     => $method
        ];

    }

    public static function get($url, $path){
        self::addRouter($url, $path, [self::METHOD_GET]);
    }

    public static function post($url, $path){
        self::addRouter($url, $path, [self::METHOD_POST]);
    }

    public static function any($url, $path){
        self::addRouter($url, $path, [self::METHOD_GET, self::METHOD_POST]);
    }

    public static function prefix($prefix){
        $router = new self();
        $router::$prefix = $prefix;
        $router::$prefixEnabled = true;
        return $router;
    }

    public function middleware(array $middleWareArray){
        self::$routeMiddleware = $middleWareArray;
        return $this;
    }

    public static function getCurrentUrl(){
        $uri = $_SERVER['REQUEST_URI'];
        if(strpos($uri, '?')){
            $uriArr = explode('?', $uri);
            return $uriArr['0'];
        }
        return $uri;
    }

    public static function getUriString(){
        $uri = $_SERVER['REQUEST_URI'];
        if(strpos('?'. $uri)){
            $uriArr = explode('?', $uri);
            return $uriArr['0'];
        }
    }


}