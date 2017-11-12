<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exchange extends CI_Controller
{
    public function rate()
    {
        $this->load->library('Exchange_lib');
        $this->exchange_lib->get_rate();
    }

    public function crontab()
    {
        $this->exchange_lib->crontab();
    }

    public function create()
    {
        $status = true;
        $code = $this->input->post('code');
        $bank = $this->exchange_lib->get_bank($code);
        if (empty($bank)) {
            log_message('info', 'code 格式不正確或空值 : '.$code);
            $status = false;
        }

        $type = $this->input->post('type');
        if ($type != 'cash' && $type != 'spot') {
            log_message('info', 'type 格式不正確或空值 : '.$type);
            $status = false;
        }

        $currency = $this->input->post('currency');
        $currency_ary = $this->config->item('currency');
        if (!in_array($currency, $currency_ary)) {
            log_message('info', 'currency 格式不正確或空值 : '.$currency);
            $status = false;
        }

        $exchange = $this->input->post('exchange');
        if (!is_numeric($exchange)) {
            log_message('info', 'exchange 格式不正確或空值 : '.$exchange);
            $status = false;
        }

        if ($status) {
            $user = $this->session->userdata('user');
            $this->exchange_lib->create($user['email'], $code, $type, $currency, $exchange);
            redirect('/home/index');
        }
    }

    public function delete()
    {
        $guid = $this->input->get('guid');

        if ($this->common_lib->check_guid($guid)) {
            $this->exchange_lib->delete($guid);
        } else {
            log_message('info', 'guid格式不正確');
            redirect('/');
        }
    }
}
