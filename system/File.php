<?php
namespace System;

class File{
    private $_file;
    public $defaultPath = __DIR__ . '/../public/upload';

    function __construct($file){
        $this->_file = $file;
    }
    
    public function isValid(){
        if(!empty($this->_file)  && $this->_file['error'] == 0){
            return true;
        }
        return false;
    }

    public function path(){
        if($this->isValid()){
            return $this->_file['tmp_name'];
        }
        return false;
    }

    public function move($directory = '', $rename = ''){
        $directory = empty($directory) ? $this->defaultPath : $directory;
        $name = empty($rename) ? $this->getClientOriginalName() : $rename;
        if(!is_dir($directory)){
            if (mkdir($directory, 0755, true)) {
            } else {
               die('目录创建失败，请检查目录权限是否可写');
            }
        }
        $target = $directory . '/' . $name;
        @move_uploaded_file($this->path(), $target);
        @chmod($target, 0666 & ~umask());
        return $target;
    }

    public function extension(){
        if($this->isValid()){
            $name = $this->_file['name'];
            if(strpos($name, '.')){
                $nameArr = explode('.', $name);
                return '.' . $nameArr[count($nameArr) - 1];
            }
        }
        return false;
    }

    public function getType(){
        if($this->isValid()){
            return $this->_file['type'];
        }
        return false;
    }

    public function getMimeType(){
        if($this->isValid()){
            $type = $this->_file['type'];
            if(strpos($type, '/')){
                $typeArr = explode('/', $type);
                return $typeArr['1'];
            }
        }
        return false;
    }

    public function getClientSize(){
        if($this->isValid()){
            return $this->_file['size'];
        }
        return false;
    }

    public function getClientOriginalName(){
        if($this->isValid()){
            return $this->_file['name'];
        }
        return false;
    }
    

}