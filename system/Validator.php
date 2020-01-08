<?php
namespace System;

class Validator{
    const REQUIRED  = 'required';
    const FILLED    = 'filled';
    const BETWEEN   = 'between';
    const MIN       = 'min';
    const MAX       = 'max';

    const String    = 'string';
    const INT       = 'int';
    const FLOAT     = 'float';
    const EMAIL     = 'email';
    const PHONE     = 'phone';
    const URL       = 'url';

    private $_fails = [];
    private $_ruleArray = [];

    function __construct(){
        $this->_ruleArray = [
            self::REQUIRED, self::FILLED, self::BETWEEN, self::String, self::INT,
            self::FLOAT, self::EMAIL, self::PHONE,self::MIN,self::MAX,self::URL
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
        } else {
            $this->matchRules($params, $key,$rulePiece);
        }
        return ;
    }

    public function matchRules($params, $key, $rulePiece){
        foreach ($rulePiece as $piece){
            if(strpos($piece, ':')){
                $pieceArr = explode(':', $piece);
                $pieceKey = $pieceArr['0'];
                $pieceValue = $pieceArr['1'];
            } else {
                $pieceKey = $piece;
                $pieceValue = '';
            }

            if(in_array($pieceKey, $this->_ruleArray)){
                switch ($pieceKey) {
                    case self::REQUIRED : $result = $this->ruleRequired($params, $key); break;
                    case self::BETWEEN:  $result= $this->ruleBetween($params, $key, $pieceValue); break;
                    case self::MIN: $result= $this->ruleMin($params, $key,$pieceValue); break;
                    case self::MAX: $result= $this->ruleMax($params, $key,$pieceValue); break;
                    case self::String: $result= $this->ruleString($params, $key,$pieceValue); break;
                    case self::INT: $result= $this->ruleInt($params, $key,$pieceValue); break;
                    case self::FLOAT: $result= $this->ruleFloat($params, $key,$pieceValue); break;
                    case self::EMAIL: $result= $this->ruleEmail($params, $key); break;
                    case self::URL: $result= $this->ruleUrl($params, $key); break;
                    case self::PHONE: $result= $this->rulePhone($params, $key); break;
                }
            }
        }
    }


    public function ruleRequired($params, $key){
        return isset($params[$key]) ? true : false;
    }

    public function ruleBetween($params, $key, $value){
        $arr = explode(',', $value);
        if(!isset($params[$key])){
            $this->_fails[] = $key . ' is ' . self::REQUIRED;
            return false;
        }
        if(!isset($arr['0']) || !isset($arr['1'])) {
            $this->_fails[] = self::BETWEEN . ' value is invalid ';
            return false;
        }

        $min = $arr['0'];
        $max = $arr['1'];

        if(mb_strlen($params[$key]) > $max || mb_strlen($params[$key]) < $min){
            $this->_fails[] = $params[$key] . ' length is not ' . self::BETWEEN . ' ' . $min . ' and ' .$max;
            return false;
        }
        return true;
    }

    public function ruleMin($params, $key, $value){
        if(!isset($params[$key])){
            $this->_fails[] = $key . ' is ' . self::REQUIRED;
            return false;
        }

        if(mb_strlen($params[$key]) < (int)$value){
            $this->_fails[] = $params[$key] . '  length is ' . self::MIN . ' than  ' . $value;
            return false;
        }
        return true;
    }

    public function ruleMax($params, $key, $value){
        if(!isset($params[$key])){
            $this->_fails[] = $key . ' is ' . self::REQUIRED;
            return false;
        }

        if(mb_strlen($params[$key]) > (int)$value ){
            $this->_fails[] = $params[$key] . '  is ' . self::MAX . ' than  ' . $value;
            return false;
        }
        return true;
    }

    public function ruleString($params, $key){
        if(!isset($params[$key])){
            $this->_fails[] = $key . ' is ' . self::REQUIRED;
            return false;
        }
        return is_string($params[$key]);
    }

    public function ruleInt($params, $key){
        if(!isset($params[$key])){
            $this->_fails[] = $key . ' is ' . self::REQUIRED;
            return false;
        }
        return is_int($params[$key]);
    }

    public function ruleFloat($params, $key){
        if(!isset($params[$key])){
            $this->_fails[] = $key . ' is ' . self::REQUIRED;
            return false;
        }
        return is_float($params[$key]);
    }

    public function ruleEmail($params, $key){
        if(!isset($params[$key])){
            $this->_fails[] = $key . ' is ' . self::REQUIRED;
            return false;
        }
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $params[$key])) {
            $this->_fails[] = $params[$key] . ' is not ' . self::EMAIL;
            return false;
        }
        return true;
    }

    public function ruleUrl($params, $key){
        if(!isset($params[$key])){
            $this->_fails[] = $key . ' is ' . self::REQUIRED;
            return false;
        }
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$params[$key])) {
            $this->_fails[] = $params[$key] . ' is not ' . self::URL;
            return false;
        }
        return true;
    }

    public function rulePhone($params, $key){
        if(!isset($params[$key])){
            $this->_fails[] = $key . ' is ' . self::REQUIRED;
            return false;
        }
        if(mb_strlen($params[$key]) != 11 || mb_substr($params[$key], 0 ,1) != 1){
            $this->_fails[] = $key . ' is not ' . self::PHONE;
            return false;
        }
        return true;
    }




    public function fails(){
        if(!empty($this->_fails)){
            return true;
        }
        return false;
    }


}