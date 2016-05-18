<?php

/**
 * Class WxCache
 * 专用于文件缓存 access_token 和 jsapi_ticket
 */
class WxCache
{
    const FILENAME_JSAPI_TICKET = 'jsapi_ticket.json';
    const FILENAME_ACCESS_TOKEN = 'access_token.json';

    /**
     * 读取缓存jsapi_ticket
     * @return false|string
     */
    public static function getJsApiTicket()
    {
        if (!file_exists(self::FILENAME_JSAPI_TICKET)) {
            return false;
        }

        $data = json_decode(file_get_contents(self::FILENAME_JSAPI_TICKET));

        if ($data->expire_time < time()) {
            return false;
        }

        return $data->jsapi_ticket;
    }

    /**
     * 全局缓存jsapi_ticket
     *
     * @param string $ticket
     */
    public static function setJsApiTicket($ticket)
    {
        $data                 = array();
        $data['expire_time']  = time() + 7000;
        $data['jsapi_ticket'] = $ticket;
        $fp                   = fopen(self::FILENAME_JSAPI_TICKET, "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
    }

    /**
     * 读取缓存access_token
     * @return false|string
     */
    public static function getAccessToken()
    {
        if (!file_exists(self::FILENAME_ACCESS_TOKEN)) {
            return false;
        }

        $data = json_decode(file_get_contents(self::FILENAME_ACCESS_TOKEN));

        if ($data->expire_time < time()) {
            return false;
        }

        return $data->access_token;
    }

    /**
     * 全局缓存access_token
     *
     * @param string $access_token
     */
    public static function setAccessToken($access_token)
    {
        $data                 = array();
        $data['expire_time']  = time() + 7000;
        $data['access_token'] = $access_token;
        $fp                   = fopen(self::FILENAME_ACCESS_TOKEN, "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
    }
}