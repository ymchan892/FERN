<?php

/**
 * 銀行匯率
 *
 * @author Shengeih Wang <shengeih@gmail.com>
 */
class Exchange_lib
{
    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->library('simple_html_dom');
    }

    public function get_rate($code, $type, $currency, $exchange)
    {
        // http://192.168.99.100/v1/exchange/data/808/Spot/USD/0.27

        // 查詢 Cash 現金 ＆ Spot 即期
        // $type = $this->CI->config->item('type');
        $find_ary = $this->CI->config->item('rate');

        $bank = $this->get_bank($code);
        $html = file_get_html($bank['url']);
        $table = $html->find('table', 0);
        // 判斷如果是空白則進行通知
        if (empty($table)) {
            echo 'empty';
            exit;
        }

        $rowData = array();
        foreach ($table->find('tr') as $row) {
            $flight = array();
            foreach ($row->find('td') as $cell) {
                // [0] => 美金 USD [1] => 29.92 [2] => 30.37 [3] => 30.12 [4] => 30.22
                $flight[] = $cell->plaintext;
            }
            $rowData[] = $flight;
        }

        // 整理變成 JSON 格式
        $rate_ary = array();
        foreach ($rowData as $row) {
            if (!empty($row)) {
                $rate_ary[] = array(
                            'Currency' => trim($row[0]),
                            'Cash_Buy' => trim($row[1]),
                            'Cash_Sell' => trim($row[2]),
                            'Spot_Buy' => trim($row[3]),
                            'Spot_Sell' => trim($row[4])
                        );
            }
        }

        // 判斷如果有 ?type=json 則直接呈現 json 資料
        if ($this->CI->input->get('type') == 'json') {
            $array = array(
              'bank' => $bank['name'],
              'data' => $rate_ary
            );
            echo json_encode($array);
            exit;
        }

        $message = '';
        foreach ($rate_ary as $rate) {
            // foreach ($find_ary as $key => $value) {
            if (strpos($rate['Currency'], $currency) !== false) {
                if ($type == 'Cash') {
                    // 判斷商業邏輯 , 是否賣出現金匯率低於某個值
                    if ($rate['Cash_Sell'] <= $exchange) {
                        $message = $bank['name'].'('.$rate['Currency'].') 現金賣出匯率('.$rate['Cash_Sell'].') 低於 您所指定的匯率('.$exchange.')';
                    } else {
                        $message = $bank['name'].'('.$rate['Currency'].') 現金賣出匯率('.$rate['Cash_Sell'].') 高於 您所指定的匯率('.$exchange.')';
                    }
                } else {
                    // 判斷商業邏輯 , 是否賣出即期匯率低於某個值
                    if ($rate['Spot_Sell'] <= $exchange) {
                        $message = $bank['name'].'('.$rate['Currency'].') 即期賣出匯率('.$rate['Spot_Sell'].') 低於 您所指定的匯率('.$exchange.')';
                    } else {
                        $message = $bank['name'].'('.$rate['Currency'].') 即期賣出匯率('.$rate['Spot_Sell'].') 高於 您所指定的匯率('.$exchange.')';
                    }
                }

                // 如果 url 沒有 slack=1 , 則進行實際發送 , 測試用
                // if (empty($this->CI->input->get('slack')) && $_SERVER['HTTP_HOST'] != '192.168.99.100') {
                //     $this->CI->common_lib->send_to_slack('#fren', $message);
                // } else {
                //     echo $message.'<br>';
                // }
                return $message;
            }
            // }
        }
    }

    /**
    * 使用銀行編號進行取得銀行
    */
    public function get_bank($code)
    {
        foreach ($this->CI->config->item('banks') as $key => $value) {
            if ($value['code'] == $code) {
                return $value;
            }
        }
    }

    public function get_type($type)
    {
        foreach ($this->CI->config->item('type') as $key => $value) {
            if ($value['value'] == $type) {
                return $value;
            }
        }
    }

    public function create($email, $code, $type, $currency, $exchange)
    {
        $this->CI->db->trans_begin();

        $bank = $this->get_bank($code);
        $type_s = $this->get_type($type);

        $insert = array(
        'guid' => $this->CI->common_lib->guid(),
        'email' => $email,
        'code' => $code,
        'code_name' => $bank['name'],
        'type' => $type,
        'type_name' => $type_s['name'],
        'currency'=>$currency,
        'exchange' => $exchange,
        'createtime' => date('Y-m-d H:i:s')
        );
        $this->CI->db->insert('exchange', $insert);
        log_message('info', $this->CI->db->last_query());

        if ($this->CI->db->trans_status() === false) {
            log_message('info', ' ERROR : ' . $this->CI->db->last_query());
            $this->CI->db->trans_rollback();
            return false;
        } else {
            $this->CI->db->trans_commit();
            return true;
        }
    }

    public function delete($guid)
    {
        $this->CI->db->where('guid', $guid);
        $this->CI->db->delete('exchange');
        log_message('info', $this->CI->db->last_query());
    }

    public function crontab()
    {
        $query = $this->CI->db->get('exchange');
        $rows = $query->result_array();

        foreach ($rows as $key => $value) {
            //http://35.194.235.183/v1/exchange/data/808/spot/JPY/0.2650
            $message .= $this->get_rate($value['code'], $value['type'], $value['currency'], $value['exchange']);
            $message .= '<br>';
        }
        echo $message;
        // echo json_encode($rows);
    }

    public function get_exchange_data()
    {
        $user = $this->CI->session->userdata('user');

        $this->CI->db->where('email', $user['email']);
        $this->CI->db->order_by('createtime', 'DESC');
        $query = $this->CI->db->get('exchange');
        return $query->result_array();
    }
}
