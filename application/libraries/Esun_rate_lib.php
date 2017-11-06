<?php

/**
 * 玉山銀行匯率
 *
 * @author Shengeih Wang <shengeih@gmail.com>
 */
class Esun_rate_lib
{
    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->library('simple_html_dom');
    }

    public function get_rate()
    {
        $html = file_get_html($this->CI->config->item('esun_rate_page'));

        $table = $html->find('table', 0);
        // 判斷如果是空白則進行通知
        if (empty($table)) {
            echo 'empty';
            exit;
        }

        $rowData = array();
        foreach ($table->find('tr') as $row) {
            // initialize array to store the cell data from each row
            $flight = array();
            foreach ($row->find('td') as $cell) {
                $flight[] = $cell->plaintext;
            }
            $rowData[] = $flight;
        }

        // 整理變成 JSON 格式
        $rate_ary = array();
        foreach ($rowData as $row) {
            if (!empty($row)) {
                $rate_ary[] = array(
                            'currency' => $row[0],
                            'buy' => $row[1],
                            'sell' => $row[2]
                        );
            }
        }

        // currency 設定
        $find_ary = $this->CI->config->item('esun_rate');
        $content = '';
        foreach ($rate_ary as $rate) {
            foreach ($find_ary as $key => $value) {
                // echo $find[0].' - '.$find[1].'<br>';
                if (strpos($rate['currency'], $key) !== false) {
                    // 判斷商業邏輯 , 是否賣出匯率低於某個值
                    // echo $rate['sell'] .' - '.$value.'<br>';

                    if ($rate['sell'] <= $value) {
                        // 進行通知
                        echo 'YES - '.$rate['currency'];
                        echo '<br>';
                        $message = $rate['currency'].' - 賣出即期匯率 : '.$rate['sell'].' 請進行購買 ';
                        $message .= '('.$this->CI->config->item('esun_rate_page').')';
                        $this->CI->common_lib->send_to_slack('#fren', $message);
                    }
                }
            }
        }
    }
}
