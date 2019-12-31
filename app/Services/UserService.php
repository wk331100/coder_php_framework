<?php
namespace App\Services;

use App\Models\UserModel;

class UserService{

    public static function getList(){
        return UserModel::getInstance()->getList();
    }

    public static function getInfo($uid){
        return UserModel::getInstance()->getInfo($uid);
    }

    public static function create($data){

    }

    public static function update($data){

    }

    public static function delete($data){

    }


}