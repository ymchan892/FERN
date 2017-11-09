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
}
