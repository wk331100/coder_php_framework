<?php

namespace App\Libs;

use Maatwebsite\Excel\Facades\Excel;

class Util
{
    /**
     * 生成随机字符
     * @param int $length
     *
     * @return string
     */
    public static function randChar($length = 8) {
        return self::makeWebCode($length);
    }


    /**
     * 生成web随机码
     * @param int $num
     * @param int $length
     *
     * @return string
     */
    public static function makeWebCode($length = 16) {
        // 字符集
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $code;
    }

}