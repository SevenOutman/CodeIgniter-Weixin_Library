<?php
require_once 'WxCache.php';
require_once 'WxUrl.php';
require_once 'WxException.php';

/**
 * Class WxJsApi
 * 专用于获取微信jsapi的signPackage
 */
class WxJsApi
{
    protected $appid;
    protected $secret;

    protected static $instance;

    protected static $jsApiList = array();

    /**
     * WxJsApi constructor.
     * 单例模式 私有构造函数
     *
     * @param array $wx_config
     */
    protected function __construct(array $wx_config)
    {
        $this->appid  = isset($wx_config['appid']) ? $wx_config['appid'] : '';
        $this->secret = isset($wx_config['secret']) ? $wx_config['secret'] : '';

        self::$jsApiList = array_merge(self::$jsApiList, $wx_config['js_api_list']);
    }

    /**
     * 单例工厂
     *
     * @param array $config
     *
     * @return WxJsApi
     */
    public static function config(array $config)
    {
        if (!self::$instance) {
            self::$instance = new WxJsApi($config);
        }

        return self::$instance;
    }

    /**
     * 获取 jsapi sign package
     *
     * @param string $url   要签名的页面
     * @param bool   $debug 是否开启debug
     *
     * @return array
     */
    public function getSignPackage($url = '', $debug = false)
    {
        $jsapiTicket = $this->getJsApiTicket();

        if (!$url) {
            $url = WxUrl::urlCurrent();
        }

        $timestamp = time();
        $nonceStr  = $this->createNonceStr();

        /**
         * ksort($data);
         * $str = '';
         * foreach ($data as $key => $value) {
         *     $str .= $key . '=' . $value . '&';
         * }
         * $str = substr($str, 0, count($str) - 2);
         * return sha1($str);
         */

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            'debug'     => $debug,
            "appId"     => $this->appid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "signature" => $signature,
            'jsApiList' => self::$jsApiList,
        );

        return $signPackage;
    }

    /**
     * 生成随机字符串
     *
     * @param int $length
     *
     * @return string
     */
    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str   = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        return $str;
    }

    /**
     * 获取jsapi_ticket
     * @return false|string
     * @throws WxException
     */
    private function getJsApiTicket()
    {
        if ($ticket = WxCache::getJsApiTicket()) {
            return $ticket;
        }

        $accessToken = $this->getAccessToken();
        $url         = WxUrl::urlJsApiTicket($accessToken);
        $res         = WxUrl::curlJSON($url);

        if (!property_exists($res, 'ticket')) {
            throw new WxException($res->errmsg, $res->errcode);
        }
        $ticket = $res->ticket;

        if ($ticket) {
            WxCache::setJsApiTicket($ticket);
        }

        return $ticket;
    }

    /**
     * 获取access_token
     * @return string
     * @throws WxException
     */
    private function getAccessToken()
    {
        if ($access_token = WxCache::getAccessToken()) {
            return $access_token;
        }

        $url = WxUrl::urlAccessToken($this->appid, $this->secret);
        $res = WxUrl::curlJSON($url);

        if (!property_exists($res, 'access_token')) {
            throw new WxException($res->errmsg, $res->errcode);
        }
        $access_token = $res->access_token;

        if ($access_token) {
            WxCache::setAccessToken($access_token);
        }

        return $access_token;
    }
}