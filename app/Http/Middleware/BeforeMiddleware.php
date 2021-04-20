<?php

namespace App\Http\Middleware;

use App\Libs\Util;
use System\Request;

class BeforeMiddleware{

    public function handle(Request $request){
        $requestId = Util::randChar();
        $request->setParam('request_id', strtoupper($requestId));
    }

}