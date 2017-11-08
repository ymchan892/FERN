<?php

//200 OK - [GET]：服务器成功返回用户请求的数据，该操作是幂等的（Idempotent）。
//$this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
//
//201 CREATED - [POST/PUT/PATCH]：用户新建或修改数据成功。
//$this->response($users, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
//
//204 NO CONTENT - [DELETE]：用户删除数据成功。
//$this->response($users, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
//
//400 INVALID REQUEST - [POST/PUT/PATCH]：用户发出的请求有错误，服务器没有进行新建或修改数据的操作，该操作是幂等的。。
//$this->response($users, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
//
//404 NOT FOUND - [*]：用户发出的请求针对的是不存在的记录，服务器没有进行操作，该操作是幂等的。
//$this->response($users, REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
//
//500 INTERNAL SERVER ERROR - [*]：服务器发生错误，用户将无法判断发出的请求是否成功。


/**
 * 匯率資料 API
 * @author Ivan Wang  <shengeih@gmail.com>
 */
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Exchange extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 讀取銀行匯率資料 :: RESTful <font color="red"><b>GET</b></font> Method
     * @version BETA
     * @date 2017/11/08
     * @author Shengeih Wang  <shengeih@gmail.com>
     * @param $id = 編號(<font color="Green"><b>1</b></font>)
     * @link http://{API_DOMAIN}/v1/exchange/data/808/Spot/USD/0.27
     */
    public function data_get()
    {
        $this->load->library('Exchange_lib');
        // http://192.168.99.100/v1/exchange/data/808/Spot/USD/0.27
        // 銀行代碼
        $code = $this->uri->segment(4); // 銀行編號
        $bank = $this->exchange_lib->get_bank($code);
        if (empty($bank)) {
            $return = $this->common_lib->output_for_json(__METHOD__, 'Failed', '未有此銀行代號', null);
            $this->response($return, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $type = $this->uri->segment(5); // Cash 現金 ＆ Spot 即期
        if ($type != 'cash' && $type != 'spot') {
            $return = $this->common_lib->output_for_json(__METHOD__, 'Failed', '請正確設定 cash(現金) 或 spot(即期)', null);
            $this->response($return, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $currency = $this->uri->segment(6); // 幣別
        $currency_ary = $this->config->item('currency');
        if (!in_array($currency, $currency_ary)) {
            $return = $this->common_lib->output_for_json(__METHOD__, 'Failed', '不允許的幣別代號', null);
            $this->response($return, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $exchange = $this->uri->segment(7); // 設定匯率
        if (!is_numeric($exchange)) {
            $return = $this->common_lib->output_for_json(__METHOD__, 'Failed', '匯率值不正確', null);
            $this->response($return, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $this->exchange_lib->get_rate($code, $type, $currency, $exchange);
    }
}
