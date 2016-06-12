<?php

/**
 * Class WxConfig
 * 微信公众号参数 在 config 文件中配置
 */
class WxConfig
{
    public static $appid;
    public static $secret;
    public static $token;

    public static function config(array $wx_config)
    {
        self::$appid  = isset($wx_config['appid']) ? $wx_config['appid'] : '';
        self::$secret = isset($wx_config['secret']) ? $wx_config['secret'] : '';
        self::$token  = isset($wx_config['token']) ? $wx_config['token'] : '';
    }
}