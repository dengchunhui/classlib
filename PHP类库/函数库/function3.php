<?php

function check_date($date) { //检查日期是否合法日期
    $dateArr = explode("-", $date);
    if (is_numeric($dateArr[0]) && is_numeric($dateArr[1]) && is_numeric($dateArr[2])) {
        return checkdate($dateArr[1], $dateArr[2], $dateArr[0]);
    }
    return false;
}

function check_time($time) { //检查时间是否合法时间
    $timeArr = explode(":", $time);
    if (is_numeric($timeArr[0]) && is_numeric($timeArr[1]) && is_numeric($timeArr[2])) {
        if (($timeArr[0] >= 0 && $timeArr[0] <= 23) && ($timeArr[1] >= 0 && $timeArr[1] <= 59) && ($timeArr[2] >= 0 && $timeArr[2] <= 59))
            return true;
        else
            return false;
    }
    return false;
}

function DateDiff($date1, $date2, $unit = "") { //时间比较函数，返回两个日期相差几秒、几分钟、几小时或几天
    switch ($unit) {
        case 's':
            $dividend = 1;
            break;
        case 'i':
            $dividend = 60;
            break;
        case 'h':
            $dividend = 3600;
            break;
        case 'd':
            $dividend = 86400;
            break;
        default:
            $dividend = 86400;
    }
    $time1 = strtotime($date1);
    $time2 = strtotime($date2);
    if ($time1 && $time2)
        return (float) ($time1 - $time2) / $dividend;
    return false;
}
