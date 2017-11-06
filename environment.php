<?php

/**
 * 依據各環境來建置常數值
 * @author Shengeih Wang <shengeih@gmail.com>
 */
// 設定時區 by Shengeih Wang @ 2017/01/17
date_default_timezone_set("Asia/Taipei");
// 加密使用
define('ENCRYPT_IV', null);
// webhooks for salck
define('WEBHOOK_FOR_SLACK', 'https://hooks.slack.com/services/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');
// 判斷抓取 HTTP_HOST 前六碼 by Shengeih Wang @2017/01/09
define('HTTP_HOST', substr($_SERVER['HTTP_HOST'], 0, 7));

// if ($_SERVER['HTTP_HOST'] == '192.168.99.100:81') { // development
//     include('environment-development.php');
// } elseif ($_SERVER['HTTP_HOST'] == '192.168.0.81') { // staging
//     include('environment-staging.php');
// } elseif ($_SERVER['HTTP_HOST'] == '172.31.24.205' || $_SERVER['HTTP_HOST'] == '52.193.11.35' || $_SERVER['HTTP_HOST'] == 'api.charmingmall.com.tw') { // production
//     include('environment-production.php');
