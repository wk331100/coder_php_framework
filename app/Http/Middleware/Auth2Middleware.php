<?php

namespace App\Http\Middleware;

use System\Request;

class Auth2Middleware{

    public function handle(Request $request){
        $request->setParam('auth2', true);
    }


}