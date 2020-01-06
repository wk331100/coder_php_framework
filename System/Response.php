<?php

namespace System;

use App\Libs\MessageCode;

class Response{
    const TYPE_HTML = 'html';
    const TYPE_JSON = 'json';

    protected static $contentType = 'text/html';
    protected static $charset = 'utf-8';
    protected static $code = 200;

    public static function html($data){
        self::setHeader(self::TYPE_HTML);
        header('Content-Type:'.self::$contentType.';charset='.self::$charset);
        return $data;
    }

    public static function json($data){
        self::setHeader(self::TYPE_JSON);
        header('Content-Type:'.self::$contentType.';charset='.self::$charset);
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
        self::setHeader(self::TYPE_JSON);
        header('Content-Type:'.self::$contentType.';charset='.self::$charset);
        $results = [
            'code' => $e->getCode(),
            'msg' => $e->getMessage()
        ];
        if (!empty($extends)) {
            return array_merge($results, $extends);
        }
        return $results;
    }


    public static function setHeader($type){
        switch ($type){
            case 'html' : self::setHeaderHtml(); break;
            case 'json' : self::setHeaderJson(); break;
            default: self::setHeaderHtml(); break;
        }

    }

    protected static function setHeaderHtml(){
        return self::$contentType =  'text/html';
    }

    protected static function setHeaderJson(){
        return self::$contentType =  'application/json';
    }



}