<?php

// 用于获取 jsapi sign package
require_once 'WxJsApi.php';

// 用于网页授权登录
require_once 'WxOAuth.php';

require_once 'WxException.php';

// 给 Wx_Controller 用
require_once 'WxUser.php';

/**
 * Class Wx
 */
class Wx
{
    protected $appid;
    protected $secret;
    protected $token;

    protected $jsapi;
    protected $oauth;

    public function __construct(array $wx_config)
    {
        $this->appid  = isset($wx_config['appid']) ? $wx_config['appid'] : '';
        $this->secret = isset($wx_config['secret']) ? $wx_config['secret'] : '';
        $this->token  = isset($wx_config['token']) ? $wx_config['token'] : '';

        $this->jsapi = WxJsApi::config($wx_config);
        $this->oauth = WxOAuth::config($wx_config);
    }

    /**
     * 设置开发者模式，接入代码。
     */
    public function check_signature()
    {
        if (empty($this->token)) {
            throw new WxException('user token is missing', 10000);
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];

        $data = array($this->token, $timestamp, $nonce);

        $result_str = self::sign($data);
        if ($result_str == $signature) {
            echo $_GET["echostr"];
        } else {
            throw new WxException('check signature fail', 10001);
        }
    }

    /**
     * 微信授权登录
     *
     * @param bool $getFullInfo
     *
     * @return mixed
     * @throws WxException
     */
    public function getUserInfo($getFullInfo = true)
    {

        return $this->oauth->getUserInfo($getFullInfo);
    }

    /**
     * @param string $url   要签名的页面
     * @param bool   $debug 是否开启debug
     *
     * @return array
     */
    public function getSignPackage($url = '', $debug = false)
    {
        return $this->jsapi->getSignPackage($url, $debug);
    }

    public static function sign($data)
    {
        sort($data, SORT_STRING);
        $str = implode($data);

        return sha1($str);
    }
}