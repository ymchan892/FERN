<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Esun extends CI_Controller
{
    public function rate()
    {
        $this->load->library('esun_rate_lib');
        $this->esun_rate_lib->get_rate();
    }
}
