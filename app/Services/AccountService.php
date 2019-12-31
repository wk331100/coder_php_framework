<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use App\Libs\MessageCode;
use App\Models\UserModel;

class AccountService{

    public static function getUserList($data){
//        if(empty($data['token'])){
//            throw new ServiceException(MessageCode::ILLEGAL_PARAMETERS);
//        }
        return UserModel::getInstance()->getList();
    }
}