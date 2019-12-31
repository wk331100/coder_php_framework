<?php
namespace System;

class Application{
    const BASE_NAMESPACE = '\App\Http\Controllers';

    public $routeMiddleware = [];
    public $beforeMiddleware = [];
    public $afterMiddleware = [];

    protected $basePath;

    public function __construct($basePath = null, $routerFiles = []){
        $this->basePath = $basePath;
        $this->bootstrapRouter($basePath, $routerFiles);
    }

    public function bootstrapRouter($basePath, $routerFiles){
        $routePath = $basePath . DIRECTORY_SEPARATOR . 'routes';
        Route::group($routePath, $routerFiles);
    }

    public function loadEnv($file = '.env'){
        $envPath = $this->basePath . '/' . $file;
        $env = parse_ini_file($envPath);
        Env::set($env);
    }

    public function routeMiddleware($routeMiddlewareNameArray = []){
        $this->routeMiddleware = $routeMiddlewareNameArray;
    }

    public function beforeMiddleware($beforeMiddlewareNameArray = []){
        $this->beforeMiddleware = $beforeMiddlewareNameArray;
    }

    public function afterMiddleware($afterMiddlewareNameArray = []){
        $this->afterMiddleware = $afterMiddlewareNameArray;
    }



    public function run(){

        $urlPath = Route::getCurrentUrl();
        if(!isset(Route::$routes[$urlPath])){
            die('路由不存在');
        }

        if(!in_array(strtolower($_SERVER['REQUEST_METHOD']),Route::$routes[$urlPath]['method'])){
            die('Method不存在');
        }

        //执行加载
        $request = new Request($_REQUEST);

        //执行前置中间件
        if(!empty($this->beforeMiddleware)){
            foreach ($this->beforeMiddleware as $middleware){
                call_user_func($middleware . '::handle', $request);
            }
        }

        //执行路由中间件
        if(!empty($this->routeMiddleware) && !empty(Route::$routeMiddleware)){
            foreach (Route::$routeMiddleware as $middleware){
                if(isset($this->routeMiddleware[$middleware])){
                    call_user_func($this->routeMiddleware[$middleware] . '::handle', $request);
                }
            }
        }


        //执行前置中间件
        if(!empty($this->routeMiddleware)){
            foreach ($this->beforeMiddleware as $middleware){
                call_user_func($middleware . '::handle', $request);
            }
        }

        //执行控制器
        $fullNamespace = self::BASE_NAMESPACE . '\\' . Route::$routes[$urlPath]['controller'];
        $controller = $fullNamespace . '::' . Route::$routes[$urlPath]['action'];
        $response = call_user_func($controller, $request);

        //执行后置中间件
        if(!empty($this->afterMiddleware)){
            foreach ($this->afterMiddleware as $middleware){
                call_user_func($middleware . '::handle', $request);
            }
        }
        echo (is_array($response) || is_object($response))? json_encode($response) : $response ;
        die();
    }


}