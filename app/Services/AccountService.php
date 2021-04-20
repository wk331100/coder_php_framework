<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use App\Libs\MessageCode;
use App\Models\UserModel;

class AccountService{

    public static function getUserList($data){
        return UserModel::getInstance()->getUserList();
    }
}