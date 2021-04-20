<?php
namespace App\Http\Controllers\Account;

use App\Exceptions\ServiceException;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use System\Request;
use System\Response;

class UserController extends Controller {

    public function index(Request $request){
        try {
//            $params = $request->all();
//            $userList = AccountService::getUserList($params);
//            $data = [
//                'params' => $params,
//                'res' => $userList
//            ];
            $data = [
                "info" => [
                    '10001' => [
                        '303' => 1,
                        '304' => 2,
                    ],
                    '10002' => [
                        '305' => 3,
                        '306' => 4,
                    ]
                ]
            ];
            return Response::json($data);
        } catch (ServiceException $e) {
            return Response::error($e);
        }
    }
}