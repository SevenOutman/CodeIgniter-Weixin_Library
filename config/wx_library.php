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