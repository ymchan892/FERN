<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        // $this->load->view('welcome_message');
    }

    public function create_user()
    {
        $this->user_lib->create('shengeih@gmail.com', '1234567890');
    }

    public function exchange_create()
    {
        $user_id = '1';
        $bank = '808';
        $type = 'spot';
        $currency = 'USD';
        $exchange = '30.1';
        $this->exchange_lib->create($user_id, $bank, $type, $currency, $exchange);
    }

    public function exchange_delete()
    {
        $id = '2';
        $this->exchange_lib->delete($id);
    }
}
