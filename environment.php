<?php
/**
 * 依據各環境來建置常數值 for WWW SERVER
 * @author Ivan Wang <ivanwang@csd.tw>
 */
// 設定時區 by Ivan Wang @ 2017/01/17
date_default_timezone_set("Asia/Taipei");
// 加密使用
define('ENCRYPT_IV', null);
// webhooks for salck
define('WEBHOOK_FOR_SLACK', 'https://hooks.slack.com/services/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');
// 判斷抓取 HTTP_HOST 前六碼 by Ivan Wang @2017/01/09
define('HTTP_HOST', substr($_SERVER['HTTP_HOST'], 0, 7));
// 是否關站 by Ivan Wang @ 2017/01/16
define('SITE_STATUS', true);
// 分頁每頁多少筆資料 by Ivan Wang @ 2017/02/09
define('PAGE_ROWS', 10);
// 設定語系檔案 by Ivan Wang @ 2017/06/06
define('LANG', 'zh-tw');
// SMTP 帳號密碼 by Ivan Wang @ 2017/06/28
define('SMTP_USER', 'xxxx');
define('SMTP_PASS', 'xxxx');

if ($_SERVER['HTTP_HOST'] == '192.168.99.100') { // development
    include('environment-development.php');
} elseif ($_SERVER['HTTP_HOST'] == '35.194.235.183') { // production
    include('environment-production.php');
}
