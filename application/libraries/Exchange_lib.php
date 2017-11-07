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

    public function get_rate()
    {
        // 指定讀取的銀行頁面
        $bank = $this->CI->config->item('bank');
        $type = $this->CI->config->item('type');
        $find_ary = $this->CI->config->item('rate');

        $html = file_get_html($this->set_bank($bank));
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
              'bank' => $bank,
              'data' => $rate_ary
            );
            echo json_encode($array);
            exit;
        }

        $message = '';
        foreach ($rate_ary as $rate) {
            foreach ($find_ary as $key => $value) {
                // echo $find[0].' - '.$find[1].'<br>';
                if (strpos($rate['Currency'], $key) !== false) {
                    if ($type == 'Cash') {
                        // 判斷商業邏輯 , 是否賣出現金匯率低於某個值
                        if ($rate['Cash_Sell'] <= $value) {
                            $message = $bank.'('.$rate['Currency'].') 現金賣出匯率('.$rate['Cash_Sell'].')低於您所指定的匯率('.$value.')';
                        }
                    } else {
                        // 判斷商業邏輯 , 是否賣出即期匯率低於某個值
                        if ($rate['Spot_Sell'] <= $value) {
                            $message = $bank.'('.$rate['Currency'].') 即期賣出匯率('.$rate['Spot_Sell'].')低於您所指定的匯率('.$value.')';
                        }
                    }

                    // 如果 url 沒有 slack=1 , 則進行實際發送 , 測試用
                    if (empty($this->CI->input->get('slack'))) {
                        $this->CI->common_lib->send_to_slack('#fren', $message);
                    } else {
                        echo $message;
                    }
                }
            }
        }
    }

    private function set_bank($bank)
    {
        if ($bank == '' || $bank == null) {
            exit;
        }

        foreach ($this->CI->config->item('banks') as $key => $value) {
            if ($value['bank'] == $bank) {
                return $value['url'];
            }
        }
    }
}
