<?php

/*
  作用：取得随机字符串
  参数：
  1、(int)$length = 32 #随机字符长度，默认为32
  2、(int)$mode = 0 #随机字符类型，0为大小写英文和数字，1为数字，2为小写子木，3为大写字母，4为大小写字母，5为大写字母和数字，6为小写字母和数字
  返回：取得的字符串
  使用：
  $code = new activeCodeObj;
  $str = $code->getCode($length, $mode);
 */

class activeCodeObj {

    function getCode($length = 32, $mode = 0) {
        switch ($mode) {
            case '1':
                $str = '1234567890';
                break;
            case '2':
                $str = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case '3':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case '4':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case '5':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                break;
            case '6':
                $str = 'abcdefghijklmnopqrstuvwxyz1234567890';
                break;
            default:
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
                break;
        }

        $result = '';
        $l = strlen($str);

        for ($i = 0; $i < $length; $i ++) {
            $num = rand(0, $l);
            $result .= $str[$num];
        }
        return $result;
    }

}

取得客户端信息

/*
  作用：取得客户端信息
  参数：
  返回：指定的资料
  使用：
  $code = new clientGetObj;
  1、浏览器：$str = $code->getBrowse();
  2、IP地址：$str = $code->getIP();
  4、操作系统：$str = $code->getOS();
 */

class clientGetObj {

    function getBrowse() {
        global $_SERVER;
        $Agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = '';
        $browserver = '';
        $Browser = array('Lynx', 'MOSAIC', 'AOL', 'Opera', 'JAVA', 'MacWeb', 'WebExplorer', 'OmniWeb');
        for ($i = 0; $i <= 7; $i ++) {
            if (strpos($Agent, $Browsers[$i])) {
                $browser = $Browsers[$i];
                $browserver = '';
            }
        }
        if (ereg('Mozilla', $Agent) && !ereg('MSIE', $Agent)) {
            $temp = explode('(', $Agent);
            $Part = $temp[0];
            $temp = explode('/', $Part);
            $browserver = $temp[1];
            $temp = explode(' ', $browserver);
            $browserver = $temp[0];
            $browserver = preg_replace('/([d.]+)/', '\1', $browserver);
            $browserver = $browserver;
            $browser = 'Netscape Navigator';
        }
        if (ereg('Mozilla', $Agent) && ereg('Opera', $Agent)) {
            $temp = explode('(', $Agent);
            $Part = $temp[1];
            $temp = explode(')', $Part);
            $browserver = $temp[1];
            $temp = explode(' ', $browserver);
            $browserver = $temp[2];
            $browserver = preg_replace('/([d.]+)/', '\1', $browserver);
            $browserver = $browserver;
            $browser = 'Opera';
        }
        if (ereg('Mozilla', $Agent) && ereg('MSIE', $Agent)) {
            $temp = explode('(', $Agent);
            $Part = $temp[1];
            $temp = explode(';', $Part);
            $Part = $temp[1];
            $temp = explode(' ', $Part);
            $browserver = $temp[2];
            $browserver = preg_replace('/([d.]+)/', '\1', $browserver);
            $browserver = $browserver;
            $browser = 'Internet Explorer';
        }
        if ($browser != '') {
            $browseinfo = $browser . ' ' . $browserver;
        } else {
            $browseinfo = false;
        }
        return $browseinfo;
    }

    function getIP() {
        global $_SERVER;
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function getOS() {
        global $_SERVER;
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $os = false;
        if (eregi('win', $agent) && strpos($agent, '95')) {
            $os = 'Windows 95';
        } else if (eregi('win 9x', $agent) && strpos($agent, '4.90')) {
            $os = 'Windows ME';
        } else if (eregi('win', $agent) && ereg('98', $agent)) {
            $os = 'Windows 98';
        } else if (eregi('win', $agent) && eregi('nt 5.1', $agent)) {
            $os = 'Windows XP';
        } else if (eregi('win', $agent) && eregi('nt 5', $agent)) {
            $os = 'Windows 2000';
        } else if (eregi('win', $agent) && eregi('nt', $agent)) {
            $os = 'Windows NT';
        } else if (eregi('win', $agent) && ereg('32', $agent)) {
            $os = 'Windows 32';
        } else if (eregi('linux', $agent)) {
            $os = 'Linux';
        } else if (eregi('unix', $agent)) {
            $os = 'Unix';
        } else if (eregi('sun', $agent) && eregi('os', $agent)) {
            $os = 'SunOS';
        } else if (eregi('ibm', $agent) && eregi('os', $agent)) {
            $os = 'IBM OS/2';
        } else if (eregi('Mac', $agent) && eregi('PC', $agent)) {
            $os = 'Macintosh';
        } else if (eregi('PowerPC', $agent)) {
            $os = 'PowerPC';
        } else if (eregi('AIX', $agent)) {
            $os = 'AIX';
        } else if (eregi('HPUX', $agent)) {
            $os = 'HPUX';
        } else if (eregi('NetBSD', $agent)) {
            $os = 'NetBSD';
        } else if (eregi('BSD', $agent)) {
            $os = 'BSD';
        } else if (ereg('OSF1', $agent)) {
            $os = 'OSF1';
        } else if (ereg('IRIX', $agent)) {
            $os = 'IRIX';
        } else if (eregi('FreeBSD', $agent)) {
            $os = 'FreeBSD';
        } else if (eregi('teleport', $agent)) {
            $os = 'teleport';
        } else if (eregi('flashget', $agent)) {
            $os = 'flashget';
        } else if (eregi('webzip', $agent)) {
            $os = 'webzip';
        } else if (eregi('offline', $agent)) {
            $os = 'offline';
        } else {
            $os = 'Unknown';
        }
        return $os;
    }

}

//修改自q3boy
class cnStrObj {

    function substrGB($str = '', $start = '', $len = '') {
        if ($start == 0 || $start == '') {
            $start = 1;
        }
        if ($str == '' || $len == '') {
            return false;
        }
        for ($i = 0; $i < $start + $len; $i ++) {
            $tmpstr = (ord($str[$i]) >= 161 && ord($str[$i]) <= 247 && ord($str[$i + 1]) >= 161 && ord($str[$i + 1]) <= 254) ? $str[$i] . $str[++$i] : $tmpstr = $str[$i];
            if ($i >= $start && $i < ($start + $len)) {
                $tmp .=$tmpstr;
            }
        }
        return $tmp;
    }

    function isGB($str) {
        $strLen = strlen($str);
        $length = 1;
        for ($i = 0; $i < $strLen; $i ++) {
            $tmpstr = ord(substr($str, $i, 1));
            $tmpstr2 = ord(substr($str, $i + 1, 1));
            if (($tmpstr <= 161 || $tmpstr >= 247) && ($tmpstr2 <= 161 || $tmpstr2 >= 247)) {
                $legalflag = false;
            } else {
                $legalflag = true;
            }
        }
        return $legalflag;
    }

}

//下载自某e文网站
/* * *************************************
 * * Filename.......: class.smtp.inc
 * * Project........: SMTP Class
 * * Version........: 1.00b
 * * Last Modified..: 30 September 2001
 * ************************************* */

define('SMTP_STATUS_NOT_CONNECTED', 1, TRUE);
define('SMTP_STATUS_CONNECTED', 2, TRUE);

class smtp {

    var $connection;
    var $recipients;
    var $headers;
    var $timeout;
    var $errors;
    var $status;
    var $body;
    var $from;
    var $host;
    var $port;
    var $helo;
    var $auth;
    var $user;
    var $pass;

    /*     * *************************************
     * * Constructor function. Arguments:
     * * $params - An assoc array of parameters:
     * *
     * * host - The hostname of the smtp server Default: localhost
     * * port - The port the smtp server runs on Default: 25
     * * helo - What to send as the HELO command Default: localhost
     * * (typically the hostname of the
     * * machine this script runs on)
     * * auth - Whether to use basic authentication Default: FALSE
     * * user - Username for authentication Default: <blank>
     * * pass - Password for authentication Default: <blank>
     * * timeout - The timeout in seconds for the call Default: 5
     * * to fsockopen()
     * ************************************* */

    function smtp($params = array()) {

        if (!defined('CRLF'))
            define('CRLF', "\r\n", TRUE);

        $this->timeout = 5;
        $this->status = SMTP_STATUS_NOT_CONNECTED;
        $this->host = 'localhost';
        $this->port = 25;
        $this->helo = 'localhost';
        $this->auth = FALSE;
        $this->user = '';
        $this->pass = '';
        $this->errors = array();

        foreach ($params as $key => $value) {
            $this->$key = $value;
        }
    }

    /*     * *************************************
     * * Connect function. This will, when called
     * * statically, create a new smtp object,
     * * call the connect function (ie this function)
     * * and return it. When not called statically,
     * * it will connect to the server and send
     * * the HELO command.
     * ************************************* */

    function connect($params = array()) {

        if (!isset($this->status)) {
            $obj = new smtp($params);
            if ($obj->connect()) {
                $obj->status = SMTP_STATUS_CONNECTED;
            }

            return $obj;
        } else {
            $this->connection = fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
            socket_set_timeout($this->connection, 0, 250000);

            $greeting = $this->get_data();
            if (is_resource($this->connection)) {
                return $this->auth ? $this->ehlo() : $this->helo();
            } else {
                $this->errors[] = 'Failed to connect to server: ' . $errstr;
                return FALSE;
            }
        }
    }

    /*     * *************************************
     * * Function which handles sending the mail.
     * * Arguments:
     * * $params - Optional assoc array of parameters.
     * * Can contain:
     * * recipients - Indexed array of recipients
     * * from - The from address. (used in MAIL FROM<img src="images/smilies/smile.gif" border="0" alt="">,
     * * this will be the return path
     * * headers - Indexed array of headers, one header per array entry
     * * body - The body of the email
     * * It can also contain any of the parameters from the connect()
     * * function
     * ************************************* */

    function send($params = array()) {

        foreach ($params as $key => $value) {
            $this->set($key, $value);
        }

        if ($this->is_connected()) {

// Do we auth or not? Note the distinction between the auth variable and auth() function
            if ($this->auth) {
                if (!$this->auth())
                    return FALSE;
            }

            $this->mail($this->from);
            if (is_array($this->recipients))
                foreach ($this->recipients as $value)
                    $this->rcpt($value);
            else
                $this->rcpt($this->recipients);

            if (!$this->data())
                return FALSE;

// Transparency
            $headers = str_replace(CRLF . '.', CRLF . '..', trim(implode(CRLF, $this->headers)));
            $body = str_replace(CRLF . '.', CRLF . '..', $this->body);
            $body = $body[0] == '.' ? '.' . $body : $body;

            $this->send_data($headers);
            $this->send_data('');
            $this->send_data($body);
            $this->send_data('.');

            return (substr(trim($this->get_data()), 0, 3) === '250');
        }else {
            $this->errors[] = 'Not connected!';
            return FALSE;
        }
    }

    /*     * *************************************
     * * Function to implement HELO cmd
     * ************************************* */

    function helo() {
        if (is_resource($this->connection)
                AND $this->send_data('HELO ' . $this->helo)
                AND substr(trim($error = $this->get_data()), 0, 3) === '250') {

            return TRUE;
        } else {
            $this->errors[] = 'HELO command failed, output: ' . trim(substr(trim($error), 3));
            return FALSE;
        }
    }

    /*     * *************************************
     * * Function to implement EHLO cmd
     * ************************************* */

    function ehlo() {
        if (is_resource($this->connection)
                AND $this->send_data('EHLO ' . $this->helo)
                AND substr(trim($error = $this->get_data()), 0, 3) === '250') {

            return TRUE;
        } else {
            $this->errors[] = 'EHLO command failed, output: ' . trim(substr(trim($error), 3));
            return FALSE;
        }
    }

    /*     * *************************************
     * * Function to implement AUTH cmd
     * ************************************* */

    function auth() {
        if (is_resource($this->connection)
                AND $this->send_data('AUTH LOGIN')
                AND substr(trim($error = $this->get_data()), 0, 3) === '334'
                AND $this->send_data(base64_encode($this->user)) // Send username
                AND substr(trim($error = $this->get_data()), 0, 3) === '334'
                AND $this->send_data(base64_encode($this->pass)) // Send password
                AND substr(trim($error = $this->get_data()), 0, 3) === '235') {

            return TRUE;
        } else {
            $this->errors[] = 'AUTH command failed: ' . trim(substr(trim($error), 3));
            return FALSE;
        }
    }

    /*     * *************************************
     * * Function that handles the MAIL FROM: cmd
     * ************************************* */

    function mail($from) {

        if ($this->is_connected()
                AND $this->send_data('MAIL FROM:<' . $from . '>')
                AND substr(trim($this->get_data()), 0, 2) === '250') {

            return TRUE;
        } else
            return FALSE;
    }

    /*     * *************************************
     * * Function that handles the RCPT TO: cmd
     * ************************************* */

    function rcpt($to) {

        if ($this->is_connected()
                AND $this->send_data('RCPT TO:<' . $to . '>')
                AND substr(trim($error = $this->get_data()), 0, 2) === '25') {

            return TRUE;
        } else {
            $this->errors[] = trim(substr(trim($error), 3));
            return FALSE;
        }
    }

    /*     * *************************************
     * * Function that sends the DATA cmd
     * ************************************* */

    function data() {

        if ($this->is_connected()
                AND $this->send_data('DATA')
                AND substr(trim($error = $this->get_data()), 0, 3) === '354') {

            return TRUE;
        } else {
            $this->errors[] = trim(substr(trim($error), 3));
            return FALSE;
        }
    }

    /*     * *************************************
     * * Function to determine if this object
     * * is connected to the server or not.
     * ************************************* */

    function is_connected() {

        return (is_resource($this->connection) AND ( $this->status === SMTP_STATUS_CONNECTED));
    }

    /*     * *************************************
     * * Function to send a bit of data
     * ************************************* */

    function send_data($data) {

        if (is_resource($this->connection)) {
            return fwrite($this->connection, $data . CRLF, strlen($data) + 2);
        } else
            return FALSE;
    }

    /*     * *************************************
     * * Function to get data.
     * ************************************* */

    function &get_data() {

        $return = '';
        $line = '';

        if (is_resource($this->connection)) {
            while (strpos($return, CRLF) === FALSE OR substr($line, 3, 1) !== ' ') {
                $line = fgets($this->connection, 512);
                $return .= $line;
            }
            return $return;
        } else
            return FALSE;
    }

    /*     * *************************************
     * * Sets a variable
     * ************************************* */

    function set($var, $value) {

        $this->$var = $value;
        return TRUE;
    }

}

// End of class
?>

