<?php

namespace System;

class Request{

    public $params;

    function __construct($params) {
        $this->params = $params;
    }

    public function all(){
        return $this->params;
    }

    public function input($key, $default = ''){
       return isset($this->params[$key]) ? $this->params[$key] : $default;
    }

    public function setParam($key, $value){
        $this->params[$key] = $value;
    }

    public function has($key){
        return isset($this->params[$key]) ? true : false;
    }

    public function path(){
        return $this->params['_url'];
    }

    public function url(){
        return  $_SERVER['REQUEST_SCHEME'] . '://' .$_SERVER['HTTP_HOST'] .$this->params['_url'];
    }

    public function fullUrl(){
        return  $_SERVER['REQUEST_SCHEME'] . '://' .$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'] ;
    }

    public function method(){
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isMethod($method){
        if(strtoupper($method) == $_SERVER['REQUEST_METHOD']){
            return true;
        }
        return false;
    }

    public function isPost(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            return true;
        }
        return false;
    }

    public function isGet(){
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            return true;
        }
        return false;
    }

    public function hasFile($fileName){
        return isset($_FILES[$fileName]) ? true: false;
    }

    public function file($fileName){
        if(!isset($_FILES[$fileName])){
            die('文件不存在');
        }
        return new File($_FILES[$fileName]);
    }



}