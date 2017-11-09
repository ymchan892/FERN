<?php
session_start();

// 環境常數控制
include('./environment.php');

require_once $_SERVER['DOCUMENT_ROOT'] . '/facebook-sdk-v5/autoload.php';

$fb = new Facebook\Facebook([
    'app_id' => FB_APP_ID,
    'app_secret' => FB_APP_SECRET,
    'default_graph_version' => 'v2.5',
        ]);

$helper = $fb->getRedirectLoginHelper();
//https://developers.facebook.com/docs/graph-api/reference/user/
$permissions = ['email']; // optional
$loginUrl = $helper->getLoginUrl('http://' . $_SERVER['HTTP_HOST'] . '/login_callback.php', $permissions);

$_SESSION['r'] = $_GET['r'];
header('Location:' . $loginUrl);
