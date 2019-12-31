<?php

namespace App\Http\Middleware;

use System\Request;

class AuthMiddleware{

    public function handle(Request $request){
        $request->setParam('auth', true);
    }


}