<?php

class WxUrl
{
    // 获取基础接口 access_token
    const URL_ACCESS_TOKEN = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
    // 如果是企业号用以下 URL 获取 access_token
    const URL_ACCESS_TOKEN_ENTERPRISE = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken';

    // 获取 jsapi_ticket
    const URL_JSAPI_TICKET = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi";
    // 如果是企业号用以下 URL 获取 ticket
    const URL_JSAPI_TICKET_ENTERPRISE = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket";

    // 网页授权 URL
    const URL_OAUTH_REDIRECT     = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    const URL_OAUTH_ACCESS_TOKEN = 'https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code';
    const URL_OAUTH_USER_INFO    = 'https://api.weixin.qq.com/sns/userinfo?lang=zh_CN';

    /**
     * 获取当前url
     * @return string
     */
    public static function urlCurrent()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url      = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        return $url;
    }

    /**
     * @param string $accessToken
     * @param bool   $isEnterprise
     *
     * @return string
     */
    public static function urlJsApiTicket($accessToken, $isEnterprise = false)
    {
        if ($isEnterprise) {
            return self::URL_JSAPI_TICKET_ENTERPRISE . "?access_token=$accessToken";
        }

        return self::URL_JSAPI_TICKET . "&access_token=$accessToken";
    }

    /**
     * @param string $appid
     * @param string $secret
     * @param bool   $isEnterprise
     *
     * @return string
     */
    public static function urlAccessToken($appid, $secret, $isEnterprise = false)
    {
        if ($isEnterprise) {
            return self::URL_ACCESS_TOKEN_ENTERPRISE . "?corpid=$appid&corpsecret=$secret";
        }

        return self::URL_ACCESS_TOKEN . "&appid=$appid&secret=$secret";
    }

    /**
     * @param        $appid
     * @param        $redirect_uri
     * @param        $scope
     * @param string $state
     *
     * @return string
     */
    public static function urlOAuthRedirect($appid, $redirect_uri, $scope = 'snsapi_base', $state = 'REDIRECT')
    {
        return self::URL_OAUTH_REDIRECT . "?appid=$appid&redirect_uri=" . urlencode($redirect_uri) . "&response_type=code&scope=$scope&state=$state#wechat_redirect";
    }

    /**
     * @param $appid
     * @param $secret
     * @param $code
     *
     * @return string
     */
    public static function urlOAuthAccessToken($appid, $secret, $code)
    {
        return self::URL_OAUTH_ACCESS_TOKEN . "&appid=$appid&secret=$secret&code=$code";
    }

    /**
     * @param $access_token
     * @param $openid
     *
     * @return string
     */
    public static function urlOAuthUserInfo($access_token, $openid)
    {
        return self::URL_OAUTH_USER_INFO . "&access_token=$access_token&openid=$openid";
    }

    /**
     * @param string $url
     *
     * @return mixed
     */
    public static function curl($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    /**
     * @param string $url
     * @param bool   $assoc
     *
     * @return mixed
     */
    public static function curlJSON($url, $assoc = false)
    {
        return json_decode(self::curl($url), $assoc);
    }

    /**
     * @param string $url
     */
    public static function redirect($url)
    {

        header("Location:$url");
        exit;
    }
}