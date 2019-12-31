<?php
namespace System;

class Validator{
    const REQUIRED  = 'required';
    const FILLED    = 'filled';
    const BETWEEN   = 'between';

    const String    = 'string';
    const INT       = 'int';
    const FLOAT     = 'float';
    const EMAIL     = 'email';
    const PHONE     = 'phone';

    private $_fails = [];
    private $_ruleArray = [];

    function __construct(){
        $this->_ruleArray = [
            self::REQUIRED, self::FILLED, self::BETWEEN, self::String, self::INT,
            self::FLOAT, self::EMAIL, self::PHONE
        ];
    }


    public static function make($params, $rules){
        $validator = new self();
        if(!empty($rules)){
            foreach ($rules as $key =>  $rule){
                $validator->ruleAdapter($params, $key, $rule);
            }
        }
        return $validator;
    }


    public function ruleAdapter($params, $key, $rule){
        $rulePiece = explode('|', $rule);
        if($rulePiece['0'] == self::FILLED){
            if(isset($params[$key])){
                unset($rulePiece['0']);
                $this->matchRules($params, $key,$rulePiece);
            }
        }




        return ;
    }

    public function matchRules($params, $key, $rulePiece){
        foreach ($rulePiece as $piece){
            if(in_array($piece, $this->_ruleArray)){
                switch ($piece) {
                    case self::REQUIRED : $result = $this->ruleRequired($params, $key);
                }
            }

            return $result;
        }
    }


    public function ruleRequired($params, $key){
        return isset($params[$key]) ? true : false;
    }






    public function fails(){
        if(!empty($this->_fails)){
            return true;
        }
        return false;
    }


}