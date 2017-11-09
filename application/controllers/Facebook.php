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

        echo $userNode;
        echo '<hr>';
        print_r($userNode);

        // 使用 email 和 fb_id 搜尋是否有該使用者 , 如果有 email 責自動更新 fb_id
        $return = $this->user_lib->get_fb_member($userNode['email'], $userNode['id']);

        // 如果回傳資料是空的 , 則要建立帳號
        if (empty($return)) {
            $this->user_lib->create($userNode['email'], $userNode['id']);
        }

        // 進行登入動作
        // $users = array(
        //     'id' => $return['data']['id'],
        //     'groupid' => $return['data']['groupid'],
        //     'username' => $return['data']['username'],
        //     'email' => $return['data']['email'],
        //     'logged_in' => true
        // );
        // $this->session->set_userdata('users', $users);

        // 登入後進行購物車同步(之前+現在) by Ivan Wang @ 2017/02/03
        // $this->cart->sync($users['id']);

        // 登入註冊後進行導向至首頁
        redirect(base_url());
    }
}
