<?php

/**
 * Class WxUser
 * 只起一个包装数据的作用
 * @property integer $wx_user_id
 * @property string  $openid
 * @property string  $nickname
 * @property integer $sex
 * @property string  $province
 * @property string  $city
 * @property string  $country
 * @property string  $headimgurl
 * @property string  $unionid
 * @property string  $date_added
 * @property string  $date_modified
 */
class WxUser
{
    public $wx_user_id;
    public $openid;
    public $nickname;
    public $sex;
    public $province;
    public $city;
    public $country;
    public $headimgurl;
    public $unionid;
    public $date_added;
    public $date_modified;

    public function __construct($data = array())
    {
        if ($data) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }
    
    /**
     * 0(640), 46, 64, 96, 132
     * @param int $size
     *
     * @return mixed
     */
    public function getAvatar($size = 640)
    {
        if ($size > 132) {
            return $this->avatarUrl(0);
        }
        if ($size > 96) {
            return $this->avatarUrl(132);
        }
        if ($size > 64) {
            return $this->avatarUrl(96);
        }
        if ($size > 46) {
            return $this->avatarUrl(64);
        }

        return $this->avatarUrl(46);
    }

    protected function avatarUrl($size)
    {
        return preg_replace('/\/\d+$/', "/$size", $this->headimgurl);
    }
}