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
            $params = $request->all();
            $userList = AccountService::getUserList($params);
            $data = [
                'params' => $params,
                'res' => $userList
            ];
            return Response::json($userList);
        } catch (ServiceException $e) {
            return Response::error($e);
        }
    }
}