<?php

namespace System;

use App\Libs\MessageCode;

class Response{

    protected static $contentType = 'application/json';
    protected static $charset = 'utf-8';
    protected static $header = [];

    public static function header($headerArray = []){
        if(!empty($headerArray)){
            self::$header = $headerArray;
        }
    }

    public static function html($data){
        self::$contentType =  'text/html';
        self::setHeader();
        return $data;
    }

    public static function json($data){
        self::setHeader();
        $data = ($data == false) ? self::failed($data) : self::success($data);
        return json_encode($data, JSON_UNESCAPED_SLASHES);
    }

    public static function success($data = [], $extends = []){
        $result = [
            'code' => MessageCode::SUCCESS,
            'msg' => MessageCode::getMessage(MessageCode::SUCCESS),
            'data' => $data
        ];

        if (!empty($extends)) {
            return array_merge($result, $extends);
        }
        return $result;
    }

    public static function failed($data = [], $extends = []) {
        self::setHeader();
        $result = [
            'code' => MessageCode::FAILED,
            'msg' => MessageCode::getMessage(MessageCode::FAILED),
            'data' => $data
        ];

        if (!empty($extends)) {
            return array_merge($result, $extends);
        }

        return $result;
    }

    public static function error(\Exception $e, $extends = []) {
        self::setHeader();
        $results = [
            'code' => $e->getCode(),
            'msg' => $e->getMessage()
        ];
        if (!empty($extends)) {
            return array_merge($results, $extends);
        }
        return $results;
    }

    private static function setHeader(){
        $headerStr = 'Content-Type:'.self::$contentType.';charset='.self::$charset;
        if(!empty(self::$header)){
            foreach (self::$header as $key => $value){
                if(!in_array($key, ['Content-Type', 'charset']))
                $headerStr .= ';' . $key . '=' . $value;
            }
        }
        header($headerStr);
    }




}