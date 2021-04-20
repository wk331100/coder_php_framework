<?php

namespace App\Models;

use System\DB;

class UserModel extends DB  {

    protected $table = 'user';
    private static $_instance;
    protected $_pk = 'uid';


    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getUserList(){
        return parent::getList(['uid','username']);
    }


}