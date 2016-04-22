<?php

function GetIP() { //获取IP
    if ($_SERVER["HTTP_X_FORWARDED_FOR"])
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if ($_SERVER["HTTP_CLIENT_IP"])
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    else if ($_SERVER["REMOTE_ADDR"])
        $ip = $_SERVER["REMOTE_ADDR"];
    else if (getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else
        $ip = "Unknown";
    return $ip;
}

function DateAdd($date, $int, $unit = "d") { //时间的增加（还可以改进成时分秒都可以增加，有时间再补上）
    $dateArr = explode("-", $date);
    $value[$unit] = $int;
    return date("Y-m-d", mktime(0, 0, 0, $dateArr[1] + $value['m'], $dateArr[2] + $value['d'], $dateArr[0] + $value['y']));
}

function GetWeekDay($date) { //计算出给出的日期是星期几
    $dateArr = explode("-", $date);
    return date("w", mktime(0, 0, 0, $dateArr[1], $dateArr[2], $dateArr[0]));
}
