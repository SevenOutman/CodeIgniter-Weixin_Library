<?php
/*
|--------------------------------------------------------------------------
| 微信公众号配置
|--------------------------------------------------------------------------
|
| 参数须与微信公众平台一致
|
*/
$config['appid']  = ''; // 必填
$config['secret'] = ''; // 必填
$config['token']  = '';

/*
|--------------------------------------------------------------------------
| JS-SDK 配置
|--------------------------------------------------------------------------
|
| 默认开启所有接口
| 接口列表见:
| http://mp.weixin.qq.com/wiki/11/74ad127cc054f6b80759c40f77ec03db.html#.E9.99.84.E5.BD.952-.E6.89.80.E6.9C.89JS.E6.8E.A5.E5.8F.A3.E5.88.97.E8.A1.A8
*/
$config['js_api_list'] = array(
    'onMenuShareTimeline',
    'onMenuShareAppMessage',
    'onMenuShareQQ',
    'onMenuShareWeibo',
    'onMenuShareQZone',
    'startRecord',
    'stopRecord',
    'onVoiceRecordEnd',
    'playVoice',
    'pauseVoice',
    'stopVoice',
    'onVoicePlayEnd',
    'uploadVoice',
    'downloadVoice',
    'chooseImage',
    'previewImage',
    'uploadImage',
    'downloadImage',
    'translateVoice',
    'getNetworkType',
    'openLocation',
    'getLocation',
    'hideOptionMenu',
    'showOptionMenu',
    'hideMenuItems',
    'showMenuItems',
    'hideAllNonBaseMenuItem',
    'showAllNonBaseMenuItem',
    'closeWindow',
    'scanQRCode',
    'chooseWXPay',
    'openProductSpecificView',
    'addCard',
    'chooseCard',
    'openCard',
);

/*
|--------------------------------------------------------------------------
| 用户缓存配置
|--------------------------------------------------------------------------
|
| 'cache_wx_user'
|
|   是否缓存微信用户信息, 默认为true。
|   设为false则不缓存, 每次调用login方法时都会引起授权登录跳转。但仍然依赖session!
|
| 'cache_type'
|
|   用何种方式缓存用户信息: database, session
|   默认为database, 会将用户信息存在数据库中, 并将id存放在session中。
|   如果设为session, 则直接将用户信息缓存在session中。
|
| 'wx_user_session_key'
|
|   用于缓存的session key
|
| 'wx_user_table_name'
|
|   用于缓存的数据库表名, 如果不存在, library将会尝试创建该表
|
*/
$config['cache_wx_user'] = true;
$config['cache_type']    = 'database';

$config['wx_user_session_key'] = 'wx_user_id';
$config['wx_user_table_name']  = 'wx_user';