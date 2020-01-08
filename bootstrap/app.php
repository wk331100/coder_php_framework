<?php
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR . '..');
define('APP_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'app');

//require __DIR__.'/../vendor/autoload.php';
require __DIR__ . '/../system/autoload.php';
require_once __DIR__ . '/../system/helpers.php';

$app = new System\Application(dirname(__DIR__), ['web']);

$file = '.env';
if (isset($_SERVER["env"])) {
    if ($_SERVER["env"] == 'test') {
        $file = '.env_test';
    } else if ($_SERVER["env"] == 'production') {
        $file = '.env_production';
    }
}

$app->loadEnv($file);

$app->beforeMiddleware([
    App\Http\Middleware\BeforeMiddleware::class,
]);
$app->afterMiddleware([
    App\Http\Middleware\AfterMiddleware::class
]);
$app->routeMiddleware([
    'auth' => App\Http\Middleware\AuthMiddleware::class,
]);

return $app;
