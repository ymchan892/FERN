<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function index()
    {
        // 判斷是否已經登入
        if (empty($this->session->userdata('user'))) {
            $login = '<a href="/fb_login.php?r='.LOGIN_RETURN_URL.'">Facebook 登入</a>';
        } else {
            $login = '<a href="/home/logout">登出</a>';
        }

        $data = array(
            'form' => form_open('exchange/create'),
            'code' => $this->_set_code_option(),
            'type' => $this->_set_type_option(),
            'currency' => $this->_set_currency_option(),
            'form_end' => '</form>',
            'exchange_data' => $this->exchange_lib->get_exchange_data(),
            'login' => $login
        );
        $this->parser->parse('home', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('/home');
    }

    public function create_user()
    {
        $this->user_lib->create('shengeih@gmail.com', '1234567890');
    }

    /**
    * DELETE FUNCTION
    */
    public function exchange_create()
    {
        $user_id = '1';
        $bank = '808';
        $type = 'spot';
        $currency = 'USD';
        $exchange = '30.1';
        $this->exchange_lib->create($user_id, $bank, $type, $currency, $exchange);
    }

    /**
    * DELETE FUNCTION
    */
    public function exchange_delete()
    {
        $id = '2';
        $this->exchange_lib->delete($id);
    }

    public function user_all()
    {
        echo json_encode($this->user_lib->all());
    }

    public function all_session()
    {
        echo json_encode($this->session->all_userdata());
    }

    public function guid()
    {
        echo $this->common_lib->guid();
    }

    public function set_session()
    {
        $this->user_lib->set_session('1863607020320170', 'shengeih@gmail.com');
    }

    public function delete()
    {
        $guid = $this->uri->segment(3);

        if ($this->common_lib->check_guid($guid)) {
            $this->exchange_lib->delete($guid);
        }
        redirect('/home');
    }

    private function _set_currency_option()
    {
        $data = '';
        foreach ($this->config->item('currency') as $row) {
            $data .= '<option value="'.$row.'">'.$row.'</option>';
        }
        return $data;
    }

    private function _set_type_option()
    {
        $data = '';
        foreach ($this->config->item('type') as $key => $value) {
            $data .= '<option value="'.$value['value'].'">'.$value['name'].'</option>';
        }
        return $data;
    }

    private function _set_code_option()
    {
        $data = '';
        foreach ($this->config->item('banks') as $key => $value) {
            $data .= '<option value="'.$value['code'].'">'.$value['code'].' '.$value['name'].'</option>';
        }
        return $data;
    }
}
