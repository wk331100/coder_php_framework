<?php

return [

    'default' => env('LOG_CHANNEL', 'single'),
    'channels' => [
        'single' => [
            'driver' => 'single',
            'path' => env('APP_LOG_PATH', storage_path('logs')) . '/error_' . date('Y-m-d') . '.log',
            'level' => 'debug',
        ],

        'request' => [
            'driver' => 'single',
            'level' => 'debug',
            'path' => env('APP_LOG_PATH', storage_path('logs')) . '/request_' . date('Y-m-d') . '.log',
        ],

        'third' => [
            'driver' => 'single',
            'level' => 'debug',
            'path' => env('APP_LOG_PATH', storage_path('logs')) . '/third_' . date('Y-m-d') . '.log',
        ],

    ]
];
