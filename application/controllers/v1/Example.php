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
 * 範例 API
 * @author Ivan Wang  <<ivanwang@csd.tw>>
 */
date_default_timezone_set("Asia/Taipei");
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Example extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 讀取會員資料 :: RESTful <font color="red"><b>GET</b></font> Method
     * @version BETA
     * @date 2017/01/09
     * @author Ivan Wang  <<ivanwang@csd.tw>>
     * @param $id = 編號(<font color="Green"><b>1</b></font>)
     * @link http://{API_DOMAIN}/v1/example/index
     */
    public function index_get()
    {
        $array = array(
          'datetime' => date('Y-m-d H:i:s'),
          'method' => __METHOD__
        );
        echo json_encode($array);
    }

    public function index_post()
    {
        $array = array(
        'datetime' => date('Y-m-d H:i:s'),
        'method' => __METHOD__
      );
        echo json_encode($array);
    }

    public function line_get()
    {
        log_message('info', "GET : ".json_encode($_GET));
    }

    public function line_post()
    {
        log_message('info', "POST : ".json_encode($_POST));
    }
}
