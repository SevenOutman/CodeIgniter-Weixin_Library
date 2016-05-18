<?php
require_once 'WxUrl.php';
require_once 'WxException.php';

/**
 * Class WxOAuth
 * 专用于微信网页授权登录
 */
class WxOAuth
{
    protected $appid;
    protected $secret;

    protected static $instance;

    /**
     * WxOAuth constructor.
     * 单例私有构造函数
     *
     * @param array $wx_config
     */
    protected function __construct(array $wx_config)
    {
        $this->appid  = isset($wx_config['appid']) ? $wx_config['appid'] : '';
        $this->secret = isset($wx_config['secret']) ? $wx_config['secret'] : '';
    }

    /**
     * 单例工厂
     *
     * @param array $config
     *
     * @return WxOAuth
     */
    public static function config(array $config)
    {
        if (!self::$instance) {
            self::$instance = new WxOAuth($config);
        }

        return self::$instance;
    }

    /**
     * 授权
     *
     * @param bool $getFullInfo
     *
     * @return mixed
     * @throws WxException
     */
    public function getUserInfo($getFullInfo = true)
    {
        if ($getFullInfo) {
            $scope = 'snsapi_userinfo';
        } else {
            $scope = 'snsapi_base';
        }

        if (!isset($_GET['code'])) {

            $this->redirectToOAuth($scope);
        } else {
            $result = $this->getOAuthAccessToken($_GET['code']);

            if ($getFullInfo) {
                $userInfo = WxOAuth::getOAuthUserInfo($result['access_token'], $result['openid']);

                return $userInfo;
            } else {
                return $result['openid'];
            }
        }
    }

    /**
     * 跳转微信授权登录, 获取 code
     *
     * @param string $scope
     */
    protected function redirectToOAuth($scope = 'snsapi_base')
    {
        $url = WxUrl::urlOAuthRedirect($this->appid, WxUrl::urlCurrent(), $scope);
        WxUrl::redirect($url);
    }

    /**
     * 获取网页授权 access_token
     *
     * @param $code
     *
     * @return array
     * @throws WxException
     */
    protected function getOAuthAccessToken($code)
    {
        $url    = WxUrl::urlOAuthAccessToken($this->appid, $this->secret, $code);
        $result = WxUrl::curlJSON($url, true);

        if (!isset($result['access_token'])) {
            throw new WxException($result['errmsg'], $result['errcode']);
        }

        return array(
            'access_token' => $result['access_token'],
            'scope'        => $result['scope'],
            'openid'       => $result['openid'],
        );
    }

    /**
     * 获取用户信息
     *
     * @param $accessToken
     * @param $openid
     *
     * @return mixed
     */
    protected static function getOAuthUserInfo($accessToken, $openid)
    {
        $url    = WxUrl::urlOAuthUserInfo($accessToken, $openid);
        $result = WxUrl::curlJSON($url, true);

        return $result;
    }
}