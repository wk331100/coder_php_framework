<?php
namespace App\Services;

use App\Models\UserModel;

class UserService{

    public static function getList(){
        return UserModel::getInstance()->getList();
    }

}