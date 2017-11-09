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
try {
    $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (isset($accessToken)) {
    header('Location: ' . VIEWURL . '/facebook/fb_proc?r=' . $accessToken);
    exit;
}
