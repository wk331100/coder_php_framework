<?php

namespace App\Http\Controllers;

use App\Exceptions\ServiceException;
use App\Libs\MessageCode;
use App\Services\UserService;
use System\Request;
use System\Response;
use System\Validator;

class UserController extends Controller {

    public function index(Request $request){
        try {
            $data = UserService::getList();
            return Response::json($data);
        } catch (ServiceException $e){
            return Response::error($e);
        }
    }

    public function info(Request $request){
        try {
            $uid = $request->input('uid');
            $data = UserService::getInfo($uid);
            return Response::json($data);
        } catch (ServiceException $e){
            return Response::error($e);
        }
    }

    public function create(Request $request){
        try {
            $params = $request->all();
            $validate = Validator::make($params, [
                'username' => 'required|between:4,12',
                'password' => 'required|min:6'
            ]);
            if($validate->fails()){
                throw new ServiceException(MessageCode::ILLEGAL_PARAMETERS);
            }
            $result = UserService::create($params);
            return Response::json($result);
        } catch (ServiceException $e){
            return Response::error($e);
        }

    }

}