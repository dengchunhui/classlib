<?php

function left($str, $len) { //解决中文被截成乱码的问题 类似ASP的LEFT
    $arr = str_split($str);
    $i = 0;
    foreach ($arr as $chr) {
        if (ord($chr) > 128)
            $add = $add ? 0 : 1;
        $i++;
        if ($i == $len)
            break;
    }
    return substr($str, 0, $len + $add);
}

function get_querystring($not_in = "Submit") { //获取$_GET和$_POST中的各项，并返回字符串
    $querystring = "";
    $GET_POST = array_merge($_POST, $_GET);
    foreach ($GET_POST as $key => $value) {
        if ($value == "" || preg_match("/$not_in/i", $key))
            continue;
        $querystring .= "$key=$value&";
    }
    if ($querystring)
        return substr($querystring, 0, -1);
    return "";
}

/*
  ++ 函数名：getMacAdd
  ++ 功 能：取得服务器MAC地址
  ++ lmhllr 2006-10-21 lmhllr#163.com
 */

function getMacAdd() {
    @exec("ipconfig /all", $array);
    for ($Tmpa; $Tmpa < count($array); $Tmpa++) {
        if (eregi("Physical", $array[$Tmpa])) {
            $mac = explode(":", $array[$Tmpa]);
            echo $mac[1];
        }
    }
}

function get_innerhtml($html, $label) { //获取一对html标记间的html字符串
    $result_arr = preg_split("/<\/" . $label . ">/i", $html);
    $pattern = "/<" . $label . ".*?>/i";
    for ($i = 0; $i < count($result_arr); $i++) {
        list($left, $right) = preg_split($pattern, $result_arr[$i], 2);
        $result_arr[$i] = $right;
    }
    return $result_arr;
}

//例: echo get_innerhtml("<tr><td height=20>something</td></tr>", "td"); //will print "something".

function get_input_value($input) { //获取Input的HTML代码中的Value值
    $pos = stripos($input, "value=") + 6;
    if ($pos !== false) {
        $input = substr($input, $pos);
        if (substr($input, 0, 1) == "\"")
            return substr($input, 1, strpos($input, "\"", 1) - 1);
        else
            return substr($input, 0, strpos($input, " ") - 1);
    }
    return false;
}

function getcontentbetween($a, $b, $str) { //获取字符串$str中,字符串$a与字符串$b之间的字符串
    if ($str !== "" && $a !== "" && $b !== "") {
        $start = strpos($str, $a) + strlen($a);
        return substr($str, $start, strpos($str, $b, $start + 1) - $start);
    }
    return false;
}

function panduan() {//判断用户是否有权限进入一个页面
    if ($_SESSION['pass']) {
        if ($_SESSION['pass'] == $pass) {
            echo "<script language=javascript>alert('欢迎使用');";
            echo"windwo.location.href='index.php';"; //index.php为目标页面
            echo"</script>";
        } else {
            echo "<script language=javascript>alert('无权使用');";
            echo"windwo.location.href='index.php';"; //index.php为目标页面
            echo"</script>";
        }
    } else {
        echo "<script language=javascript>alert('没有此用户');";
        echo"windwo.location.href='index.php';"; //index.php为目标页面
        echo"</script>";
    }
}

/**
 * 生成随机指定个数指定格式的字符串
 * @param type $length 长度 默认32位
 * @param type $mode   类型 默认大小写和数字 1数字 2小写字母 3大写字母 4大小写字母 5大写字母+数字 6小写字母+数字 7大小写字母+数字
 * @return string
 */
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
    $max = strlen($str) - 1;
    for ($i = 0; $i < $length; $i++) {
        $result .= $str[mt_rand(0, $max)];
    }
    return $result;
}

function editor_json_code($array, $code, $aid) {
    if (empty($code) OR empty($aid)) {
        return $array;
    }
    $json = json_decode($array, true);
    foreach ($json as $key => $value) {
        if ($key == $code) {
            $var[$key] = $aid;
        } else {
            $var[$key] = $value;
        }
    }
    if (!preg_match("/$code/", $array)) {
        $var[$code] = $aid;
    }
    return json_encode($var);
}
