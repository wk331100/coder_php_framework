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


}