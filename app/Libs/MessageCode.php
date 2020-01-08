<?php
namespace App\Libs;

class MessageCode {

    # 正常
    const UNDEFINED_ERROR               = 100;  # 未知错误
    const SUCCESS                       = 200;  # 成功
	const FAILED                        = 201;  # 并非请求接口失败,表示数据为空,失败
    const SYSTEM_ERROR                  = 250;  # 系统错误 请联系管理员
    const ILLEGAL_PARAMETERS            = 400;  # 非法参数
    const PARAMETERS_ERROR              = 401;  # 参数缺失
	const NO_PERMISSION                 = 403;  # 无权限访问
    const ILLEGAL_REQUEST               = 404;  # 非法请求
    const IMAGE_EXTENSION_ERROR         = 405;  # 图片格式错误




    #  ============== 业务层自定义Message ================
    private static $message = [
	    100                 => '未知错误',
	    200                 => '成功',
	    201                 => '失败',
	    202                 => '暂无数据',
	    250                 => '系统错误，请联系管理员',
	    400                 => '非法参数',
        401                 => '参数缺失',
	    403                 => '无权限访问',
	    404                 => '非法请求',
        405                 => '图片格式错误',

        1001                => '',


    ];


	/**
	 * 获取错误码中文信息
	 * @param $code
	 * @return mixed
	 */
    public static function getMessage($code){
        return isset(self::$message[$code]) ? self::$message[$code] : self::$message[self::UNDEFINED_ERROR];
    }



}
