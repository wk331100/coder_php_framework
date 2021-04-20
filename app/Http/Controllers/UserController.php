<?php

namespace App\Http\Controllers;

use App\Exceptions\ServiceException;
use App\Libs\MessageCode;
use App\Services\UserService;
use System\Request;
use System\Response;
use System\Validator;

class UserController extends Controller {
    //获取列表
    public function index(Request $request){
        try {
            $data = UserService::getList();
            return Response::json($data);
        } catch (ServiceException $e){
            return Response::error($e);
        }
    }

    //获取详情
    public function info(Request $request){
        try {
            $params = $request->all();
            $validate = Validator::make($params, [  //参数验证，具体规则参考 Validator类
                'uid' => 'required',
            ]);
            if($validate->fails()){
                throw new ServiceException(MessageCode::ILLEGAL_PARAMETERS);
            }
            $data = UserService::getInfo($params);  //调用服务
            return Response::json($data);  //Response 类按照Json格式输出
        } catch (ServiceException $e){  //捕获异常
            return Response::error($e);
        }
    }

    public function create(Request $request){
        try {
            $params = $request->all();
            $validate = Validator::make($params, [
                'username' => 'required|min:4',
                'password' => 'required|between:6,20'
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

    public function update(Request $request){
        try {
            $params = $request->all();
            $validate = Validator::make($params, [
                'uid' => 'required',
            ]);
            if($validate->fails()){
                throw new ServiceException(MessageCode::ILLEGAL_PARAMETERS);
            }

            $result = UserService::update($params);
            return Response::json($result);
        } catch (ServiceException $e){
            return Response::error($e);
        }
    }

    public function delete(Request $request){
        try {
            $params = $request->all();
            $validate = Validator::make($params, [
                'uid' => 'required',
            ]);
            if($validate->fails()){
                throw new ServiceException(MessageCode::ILLEGAL_PARAMETERS);
            }

            $result = UserService::delete($params);
            return Response::json($result);
        } catch (ServiceException $e){
            return Response::error($e);
        }
    }

}