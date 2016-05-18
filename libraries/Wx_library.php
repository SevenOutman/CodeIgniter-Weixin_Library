<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/weixin/Wx.php';

/**
 * Class Wx_library
 */
class Wx_library
{
    /** @var Wx $wx */
    private $wx;

    /** @var CI_Controller $CI */
    private $CI;

    public function __construct(array $config)
    {
        $this->CI = &get_instance();
        $this->wx = new Wx($config);

        if (!$this->checkWxUserTable()) {
            show_error("Table `wx_user` doesn't exist.");
        }
    }

    /**
     * 检查 wx_user 表是否存在 不存在则尝试创建
     * @return bool
     */
    protected function checkWxUserTable()
    {
        $db = $this->CI->db;
        if ($db->table_exists('wx_user')) {
            return true;
        }

        return $this->createWxUserTable();
    }

    /**
     * 创建 wx_user 表, 返回执行结果
     * @return bool
     */
    protected function createWxUserTable()
    {
        $db     = $this->CI->db;
        $sql    = array(
            'SET NAMES utf8;',
            'SET FOREIGN_KEY_CHECKS = 0;',
            'DROP TABLE IF EXISTS `wx_user`;',

            'CREATE TABLE `wx_user` (' .
            '`wx_user_id` INT(11) NOT NULL AUTO_INCREMENT,' .
            '`openid` VARCHAR(32) NOT NULL,' .
            '`nickname` VARCHAR(32) DEFAULT NULL,' .
            '`sex` INT(2) DEFAULT NULL,' .
            '`province` VARCHAR(16) DEFAULT NULL,' .
            '`city` VARCHAR(16) DEFAULT NULL,' .
            '`country` VARCHAR(16) DEFAULT NULL,' .
            '`headimgurl` VARCHAR(255) DEFAULT NULL,' .
            '`unionid` VARCHAR(32) DEFAULT NULL,' .
            '`date_added` DATETIME NOT NULL,' .
            '`date_modified` DATETIME NOT NULL,' .
            'PRIMARY KEY  (`wx_user_id`),' .
            'UNIQUE KEY `openid` (`openid`)' .
            ') ENGINE=MyISAM DEFAULT CHARSET=utf8;',

            'SET FOREIGN_KEY_CHECKS = 1;',
        );
        $result = true;

        foreach ($sql as $query) {
            $result = $result && $db->simple_query($query);
        }

        return $result;
    }

    /**
     * 微信授权登录
     * 将微信用户加入 wx_user 表,
     * wx_user_id 插入 session,
     * 跳回 return_url
     * @return bool
     * 登录成功返回 true, 否则返回 false
     */
    public function login()
    {
        try {
            $userInfo = $this->wx->getUserInfo(true);

            $data = array(
                'openid'        => $userInfo['openid'],
                'nickname'      => $userInfo['nickname'],
                'sex'           => $userInfo['sex'],
                'province'      => $userInfo['province'],
                'city'          => $userInfo['city'],
                'country'       => $userInfo['country'],
                'headimgurl'    => $userInfo['headimgurl'],
                'unionid'       => $userInfo['unionid'],
                'date_modified' => date('Y-m-d H:i:s'),
            );

            /** @var object|null $wx_user */
            $wx_user = $this->CI->db->get_where('wx_user', array('openid' => $data['openid']))
                                    ->row();
            if (!$wx_user) {
                $data['date_added'] = $data['date_modified'];
                $this->CI->db->insert('wx_user', $data);
                $wx_user_id = $this->CI->db->insert_id();
            } else {
                $this->CI->db
                    ->where('wx_user_id', $wx_user->wx_user_id)
                    ->update('wx_user', $data);
                $wx_user_id = $wx_user->wx_user_id;
            }
            $this->CI->session->set_userdata('wx_user_id', $wx_user_id);

            $this->redirectCleanUrl();
        }
        catch (WxException $e) {
            return false;
        }
    }

    /**
     * 获取 signPackage
     *
     * @param $url
     *
     * @return array
     * @throws WxException
     */
    public function signPackage($url = '')
    {
        return $this->wx->getSignPackage($url);
    }

    /**
     * 登录成功后去除 url 中的 code 和 state 参数
     */
    protected function redirectCleanUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url      = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $clean = preg_replace('/[\?|&](code|state)=[^&]+/', '', $url);
        header("Location:$clean");
        exit;
    }
}