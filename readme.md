# Coder - Simple PHP Framework
<p>
<a href="https://travis-ci.org/wk331100/coder_php_framework"><img src="https://travis-ci.org/wk331100/coder_php_framework.svg" alt="Build Status"></a>
</p>

Simple, Faster, No Dependence, High Performance PHP framework. 

## Introduction
Coder PHP Framework (or simply CPF). It's very very easy to use,  Just Download and Running. No Dependence, No required Composer. 
You Just need to download to your  PHP Server.

## Features
- Use CSM structure, Controller(C),Model(M),Service(S)
- Return By Json: {"code":"200", "msg":"success", "data"=[]}
- Strong early warning mechanism (DB, Cache, Script, PHP Error)
- Support ENV file
- Support Middleware

## Install
### Requirements
- PHP 7.0 +
- PDO PHP Extension
- Mbstring PHP Extension

### DownLoad
```
git clone https://github.com/wk331100/coder_php_framework.git
```


### Documentation

http://docs.getcoder.cn

## Get Started

#### Layout
```
- app
    - Exception
    - Http
	- Controllers
	- Middleware
    - Libs
    - Models
    - Services
- bootstrap
    - app.php
- config
    - app.php
    - database.php
    - logging.php
- public
    - index.php
- routes
     web.php
- storage

```

### Edit `.env` file

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coder
DB_USERNAME=coder
DB_PASSWORD=123456
```

### public/index.php
```php
<?php

$app = require_once __DIR__.'/../bootstrap/app.php';

$app->run();
```

### DEfault Route
In `routes/web.php` file
```
Route::get('/', 'IndexController@index');
```

### Default Controller
In `app/Http/Controllers/IndexController.php` file
```php
<?php

namespace App\Http\Controllers;

use System\Response;

class IndexController extends Controller {

    public function index(){
        return 'Hello World!';
    }

}
```

### Example Service

In `App/Services/UserService.php` file
```php
<?php
namespace App\Services;

use App\Models\UserModel;

class UserService{

    public static function getList(){
        return UserModel::getInstance()->getList();
    }

}
```

### Example Model

In `App/Models/UserModel.php` file

```php
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


}

```


### Output Results
```
{
    "code": 200,
    "msg" ："Success"
    "data": "Hello World"
}
```

## License
Coder PHP Framework is open source software under the [PHP License v3.01](http://www.php.net/license/3_01.txt)
