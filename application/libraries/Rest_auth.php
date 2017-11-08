<?php

/**
 * RESTful API 授權驗正
 *
 * @author Ivan Wang <ivanwang@csd.tw>
 */
class Rest_auth
{

    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function check()
    {
        // 載入 /application/config/rest.php
        $this->CI->load->config('rest');
        //讀取 $config['rest_valid_logins'] 的資料 , 授權的帳號資料放在這邊
        $logins = $this->CI->config->item('rest_valid_logins');

        if ($logins[$_SERVER['PHP_AUTH_USER']] == $_SERVER['PHP_AUTH_PW'])
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}
