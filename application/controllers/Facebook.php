<?php

//session_start();
defined('BASEPATH') or exit('No direct script access allowed');

class Facebook extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function fb_proc()
    {
        error_reporting(0);
        require_once $_SERVER['DOCUMENT_ROOT'] . '/facebook-sdk-v5/autoload.php';
        $fb = new Facebook\Facebook([
            'app_id' => FB_APP_ID,
            'app_secret' => FB_APP_SECRET,
            'default_graph_version' => 'v2.5',
        ]);

        $fb->setDefaultAccessToken($this->input->get('r'));
        $response = $fb->get('/me?fields=name,email');
        $userNode = $response->getGraphUser();

        // 使用 email 和 fb_id 搜尋是否有該使用者 , 如果有 email 責自動更新 fb_id
        $return = $this->user_lib->get_fb_member($userNode['email'], $userNode['id']);

        // 如果回傳資料是空的 , 則要建立帳號
        if (empty($return)) {
            $this->user_lib->create($userNode['email'], $userNode['id']);
        }

        // 從 facebook 取得到的 email & id 進行登入使用
        $user = array(
            'id' => $userNode['id'],
            'email' => $userNode['email'],
            'logged_in' => true
        );
        $this->session->set_userdata('user', $user);

        // 登入註冊後進行導向至首頁
        redirect("/login");
    }
}
