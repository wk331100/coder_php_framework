<?php

namespace App\Exceptions;
use App\Libs\MessageCode;
use Exception;

class DBException extends Exception {

    public function __construct($code = 3001)
    {
        $message = MessageCode::getMessage($code);
        parent::__construct($message, $code);
    }

}
