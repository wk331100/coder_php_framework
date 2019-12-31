<?php

namespace App\Http\Controllers;

use System\Response;

class HomeController extends Controller {

    public function index(){
        $data = 'Hello Coder!';
        return Response::json($data);
    }

}