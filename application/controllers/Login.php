<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function index()
    {
        // 判斷是否已經登入
        if (empty($this->session->userdata('user'))) {
            echo '尚未登入';
        } else {
            echo '已經登入';
        }
        $this->load->view('login');
    }
}
