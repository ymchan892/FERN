<?php

/**
 * API Server 專用的 common_lib (勿跟 View Server 共用)
 *
 * @author Ivan Wang
 */
class Common_lib
{
    public function __construct()
    {
        $this->CI = & get_instance();
    }

    /**
     * GUID 製造器
     */
    public function guid($namespace = '')
    {
        if ($_SERVER['HTTP_USER_AGENT'] == '') {
            $http_user_agent = '';
        } else {
            $http_user_agent = $_SERVER['HTTP_USER_AGENT'];
        }

        $_SERVER['LOCAL_ADDR'] = '';
        if ($_SERVER['LOCAL_ADDR'] == '') {
            $local_addr = '';
        } else {
            $local_addr = $_SERVER['LOCAL_ADDR'];
        }

        $_SERVER['LOCAL_PORT'] = '';
        if ($_SERVER['LOCAL_PORT'] == '') {
            $local_port = '';
        } else {
            $local_port = $_SERVER['LOCAL_PORT'];
        }

        static $guid = '';
        $uid = uniqid("", true);
        $data = $namespace;
        $data .= $_SERVER['REQUEST_TIME'];
        $data .= $http_user_agent;
        $data .= $local_addr;
        $data .= $local_port;
        $data .= $_SERVER['REMOTE_ADDR'];
        $data .= $_SERVER['REMOTE_PORT'];
        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
        $guid = substr($hash, 0, 8) . '-'
                . substr($hash, 8, 4) . '-'
                . substr($hash, 12, 4) . '-'
                . substr($hash, 16, 4) . '-'
                . substr($hash, 20, 12);
        return $guid;
    }

    /**
     * 檢驗 GUID 格式是否正確
     */
    public function check_guid($guid)
    {
        $GuidArray = explode('-', $guid);
        if (strlen($GuidArray[0]) == 8 and strlen($GuidArray[1]) == 4 and strlen($GuidArray[2]) == 4 and strlen($GuidArray[3]) == 4 and strlen($GuidArray[4]) == 12) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 3DES 加密函式
     */
    public function encrypt_for_3des($paintext)
    {
        // 根據 PKCS#7 RFC 5652 Cryptographic Message Syntax (CMS) 修正 Message 加入 Padding
        $block = mcrypt_get_block_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_ECB);
        $pad = $block - (strlen($paintext) % $block);
        $paintext .= str_repeat(chr($pad), $pad);
        $passcrypt = mcrypt_encrypt(MCRYPT_TRIPLEDES, $this->CI->config->item('encryption_key'), $paintext, MCRYPT_MODE_ECB, ENCRYPT_IV);
        return base64_encode($passcrypt);
    }

    /**
     * 3DES 解密函式
     */
    public function decrypt_for_3des($ciphertext)
    {
        if ($ciphertext != '') {
            $str = mcrypt_decrypt(MCRYPT_TRIPLEDES, $this->CI->config->item('encryption_key'), base64_decode($ciphertext), MCRYPT_MODE_ECB, ENCRYPT_IV);
            // 根據 PKCS#7 RFC 5652 Cryptographic Message Syntax (CMS) 修正 Message 移除 Padding
            $pad = ord($str[strlen($str) - 1]);
            $paintext = substr($str, 0, strlen($str) - $pad);
        } else {
            $paintext = $ciphertext;
        }
        return $paintext;
    }

    /**
     * 判斷是否為 JSON 格式
     */
    public function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    /**
     * 判斷是否為標準日期&時間格式 (2016-06-14 20:27:10)
     * @param type $input_date
     * @return boolean
     */
    public function isDateTime($input_date)
    {
        $reg_one = "/^(\d{4})\-(\d{1,2})\-(\d{1,2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/";

        //檢查格式後用php function做日期檢查
        if (preg_match($reg_one, $input_date, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }
        return false;
    }

    /**
     * 判斷是否為標準日期格式 (2016-06-14)
     * @param type $input_date
     * @return boolean
     */
    public function isDate($input_date)
    {
        $reg_one = "/^(\d{4})\-(\d{1,2})\-(\d{1,2})$/";
        //檢查格式後用php function做日期檢查
        if (preg_match($reg_one, $input_date, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }
        return false;
    }

    /**
     * 判斷是否為標準時間格式 (20:27:10)
     * @param type $input_date
     * @return boolean
     */
    public function isTime($input_date)
    {
        $reg_two = "/^([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/";

        //檢查格式後用php function做日期檢查
        if (preg_match($reg_two, $input_date, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }
        return false;
    }

    /**
     * 建立密碼 , 六位數字
     */
    public function get_password()
    {
        $password_len = 6;
        $password = '';

        $word = '1234567890';
        $len = strlen($word);

        for ($i = 0; $i < $password_len; $i++) {
            $password .= $word[rand() % $len];
        }

        return $password;
    }

    /**
    * 寄發郵件
    */
    public function send_email($to, $subject, $message, $cc = null)
    {
        $this->CI->email->from('shengeih@gmail.com');
        $this->CI->email->to($to);

        if (!empty($cc)) {
            $this->CI->email->cc($cc);
        }

        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        $this->CI->email->send();
        log_message('info', $this->CI->email->print_debugger());
    }

    /**
     * 傳送訊息到 Slack
     */
    public function send_to_slack($channel, $text)
    {
        $payload = array(
            'channel' => $channel,
            'username' => 'Rate',
            'text' => $text,
            'icon_emoji' => ':ghost:'
        );

        $data = array(
            'payload' => json_encode($payload),
        );

        // 變更程式碼 , 將 curl 獨立拉出去 by Ivan Wang @ 2017/02/14
        $this->curl_post(WEBHOOK_FOR_SLACK, $data);

        // disabled by Ivan Wang @ 2017/02/14
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//        curl_setopt($ch, CURLOPT_URL, WEBHOOK_FOR_SLACK);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
//        $buffer = curl_exec($ch);
//        curl_close($ch);
    }

    /**
     * 檢查陣列是否為空的
     * @param type $post
     * @param type $chk_ary
     * @return type
     */
    public function is_input_empty($post, $chk_ary)
    {
        foreach ($chk_ary as $col) {
            if ($post[$col] == '') {
                return $col;
            }
        }
    }

    /**
     * API 回傳的 JSON 格式
     */
    public function output_for_json($method, $status, $message = null, $data = null, $info = null)
    {
        $return = array(
            // 增加 method 回傳值 by Ivan Wang @ 2017/01/26
            'method' => $method,
            'status' => $status,
        );

        // 判斷 error 如果不是空字串就輸出 by Ivan Wang @ 2017/01/26
        if ($message != '') {
            $return['message'] = $message;
        }

        // 判斷 data 如果不是空字串就輸出 by Ivan Wang @ 2017/01/26
        if ($data != '') {
            $return['data'] = $data;
        }

        // 增加陣列欄位說明 by Ivan Wang @ 2017/02/20
        if ($info != '') {
            $return['info'] = $info;
        }

        log_message('info', $method . ' : ' . json_encode($return));

        return $return;
    }

    /**
     * 檢查市話格式 (02-123456789#123)
     */
    public function chk_phone($data)
    {
        // 濾掉 '-' 和 '#'
        $data = str_replace(array('-', '#'), array(''), $data);
        // 判斷變數是否為數字或數字的字串
        if (!is_numeric($data)) {
            return false;
        }

        return true;
    }

    /**
     * 檢查行動電話格式(0912-123456)
     */
    public function chk_mobile($data)
    {
        // 濾掉 '-'
        $data = str_replace(array('-'), array(''), $data);

        // 判斷變數是否為數字或數字的字串
        // 檢查是否手機長度為 10
        // 檢查是否前兩碼為 09
        if (!is_numeric($data) || strlen($data) != 10 || substr($data, 0, 2) != '09') {
            return false;
        }

        return true;
    }

    /**
     * 檢查是否為 IP 格式(123.)
     */
    public function isIP($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * CURL POST
     * @return type
     */
    public function curl_post($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $buffer = curl_exec($ch);
        curl_close($ch);
        return $buffer;
    }

    /**
     * 將 timestamp 轉化為 datetime (YYYY-mm-ddd HH:ii:ss) 格式
     */
    public function to_datetime($timestamp)
    {
        return date('Y-m-d H:i:s', $timestamp);
    }
}
