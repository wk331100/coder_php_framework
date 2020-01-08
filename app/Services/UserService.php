<?php
namespace App\Services;

use App\Exceptions\ServiceException;
use App\Libs\MessageCode;
use App\Models\UserModel;

class UserService{

    public static function getList(){
        return UserModel::getInstance()->getList();
    }

    public static function getInfo($data){
        if(empty($data['uid'])){
            throw new ServiceException(MessageCode::PARAMETERS_ERROR);
        }
        return UserModel::getInstance()->getInfo($data['uid']);
    }

    public static function create($data){
        if(empty($data['username']) || empty($data['password'])){
            throw new ServiceException(MessageCode::PARAMETERS_ERROR);
        }

        $insertData = [
            'username' => $data['username'],
            'password' => md5($data['password'])
        ];

        return UserModel::getInstance()->create($insertData);
    }

    public static function update($data){
        if(empty($data['uid'])){
            throw new ServiceException(MessageCode::PARAMETERS_ERROR);
        }
        $updateData = [];

        if(!empty($data['username'])){
            $updateData['username'] = $data['username'];
        }

        if(!empty($data['password'])){
            $updateData['password'] = md5($data['password']);
        }

        return UserModel::getInstance()->update($updateData, $data['uid']);
    }

    public static function delete($data){
        if(empty($data['uid'])){
            throw new ServiceException(MessageCode::PARAMETERS_ERROR);
        }
        return UserModel::getInstance()->delete($data['uid']);
    }

}