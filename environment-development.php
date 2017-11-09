<?php

define('IS_DEV_SITE', true);
// 修正常數值 by Ivan Wang @2017/01/24
define('IS_STAGING_SITE', false);
define('IS_PRODUCTION_SITE', false);

// Database Setup
define('DB_HOSTNAME', substr($_SERVER['HTTP_HOST'], 0));
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '123456');
define('DB_DATABASE', 'dev-rwd-db');

// RESTful API Setting
define('API_KEY', 'xxxx');
define('HTTP_USER', 'xxxx');
define('HTTP_PASS', 'xxxx');

//指定 APIURL 路徑
define('APIURL', 'http://' . $_SERVER['HTTP_HOST'] . ':81');
// 增加 VIEWURL by Ivan Wang @ 2017/02/21
define('VIEWURL', 'http://' . $_SERVER['HTTP_HOST']);

// Facebook APP by Ivan Wang by 2017/02/24
define('FB_APP_ID', 'xxxx');
define('FB_APP_SECRET', 'xxxx');
// 當下網址 , 登入後轉回
define('LOGIN_RETURN_URL', base64_encode($_SERVER['REQUEST_URI']));
