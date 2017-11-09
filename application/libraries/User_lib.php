<?php

/**
 * 使用者 Libraries
 * @author Shengeih Wang
 */
class User_lib
{
    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function check_login($account, $password, $ip)
    {
        // 進行加料編碼
        $password = $this->_user_password_md5($password);

        $this->CI->db->select('id,groupid,username,email');
        $this->CI->db->where('username', $account);
        $this->CI->db->where('password', $password);
        $this->CI->db->where('is_visible', '0'); // 0 : 帳號未停用 by Shengeih Wang @ 2017/02/06
        $query = $this->CI->db->get('onephp_user');
        $row = $query->row_array();
    }

    public function create($email, $fb)
    {
        $this->CI->db->trans_begin();

        $insert = array(
          'email' => $email,
          'fb' => $fb,
          'createtime' => date('Y-m-d H:i:s')
        );
        $this->CI->db->insert('users', $insert);

        if ($this->CI->db->trans_status() === false) {
            log_message('info', ' ERROR : ' . $this->CI->db->last_query());
            $this->CI->db->trans_rollback();
            return false;
        } else {
            $this->CI->db->trans_commit();
            return true;
        }
    }

    /**
     * 搜尋是否有 fb member
     */
    public function get_fb_member($email, $fb)
    {
        // $this->CI->db->select('id,groupid,username,email');
        $this->CI->db->where('email', $email);
        $this->CI->db->where('fb', $fb);
        $query = $this->CI->db->get('users');
        return $query->row_array();
    }
}
