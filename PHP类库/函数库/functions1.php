<?php

/**
 * 此函数将utf8编码字串转为unicode编码字符串
 * 参数 str ,utf8编码的字符串。
 * 参数 order,存放数据格式，是big endian还是little endian，默认的unicode存放次序是little.
 * 如："大"的unicode码是 5927。little方式存放即为：27 59 。big方式则顺序不变：59 27.
 * little 存放格式文件的开头均需有FF FE。big 存放方式的文件开头为 FE FF。否则。将会产生严重混乱。
 * 本函数只转换字符，不负责增加头部。
 * iconv转换过来的字符串是 big endian存放的。
 * 返回 ucs2string , 转换过的字符串。
 * 感谢唠叨（xuzuning）
 */
function utf8ToUnicode($str, $order = "little") {
    $ucs2string = "";
    $n = strlen($str);
    for ($i = 0; $i < $n; $i++) {
        $v = $str[$i];
        $ord = ord($v);
        if ($ord <= 0x7F) { //  0xxxxxxx 
            if ($order == "little") {
                $ucs2string .= $v . chr(0);
            } else {
                $ucs2string .= chr(0) . $v;
            }
        } elseif ($ord < 0xE0 && ord($str[$i + 1]) > 0x80) {  //110xxxxx 10xxxxxx
            $a = (ord($str[$i]) & 0x3F ) << 6;
            $b = ord($str[$i + 1]) & 0x3F;
            $ucsCode = dechex($a + $b);   //echot($ucsCode);
            $h = intval(substr($ucsCode, 0, 2), 16);
            $l = intval(substr($ucsCode, 2, 2), 16);
            if ($order == "little") {
                $ucs2string .= chr($l) . chr($h);
            } else {
                $ucs2string .= chr($h) . chr($l);
            }
            $i++;
        } elseif ($ord < 0xF0 && ord($str[$i + 1]) > 0x80 && ord($str[$i + 2]) > 0x80) { //1110xxxx 10xxxxxx 10xxxxxx
            $a = (ord($str[$i]) & 0x1F) << 12;
            $b = (ord($str[$i + 1]) & 0x3F ) << 6;
            $c = ord($str[$i + 2]) & 0x3F;
            $ucsCode = dechex($a + $b + $c);   //echot($ucsCode);
            $h = intval(substr($ucsCode, 0, 2), 16);
            $l = intval(substr($ucsCode, 2, 2), 16);
            if ($order == "little") {
                $ucs2string .= chr($l) . chr($h);
            } else {
                $ucs2string .= chr($h) . chr($l);
            }
            $i +=2;
        }
    }
    return $ucs2string;
}

// end func

/*
 * 此函数将unicode编码字串转为utf8编码字符串
 * 参数 str ,unicode编码的字符串。
 * 参数 order ,unicode字串的存放次序，为big endian还是little endian.
 * 返回 utf8string , 转换过的字符串。
 *
 */

function unicodeToUtf8($str, $order = "little") {
    $utf8string = "";
    $n = strlen($str);
    for ($i = 0; $i < $n; $i++) {
        if ($order == "little") {
            $val = dechex(ord($str[$i + 1])) . dechex(ord($str[$i]));
        } else {
            $val = dechex(ord($str[$i])) . dechex(ord($str[$i + 1]));
        }
        $val = intval($val, 16); //由于上次的.连接，导致$val变为字符串，这里得转回来。
        $i++; //两个字节表示一个unicode字符。
        $c = "";
        if ($val < 0x7F) {        // 0000-007F
            $c .= chr($val);
        } elseif ($val < 0x800) { // 0080-0800
            $c .= chr(0xC0 | ($val / 64));
            $c .= chr(0x80 | ($val % 64));
        } else {                // 0800-FFFF
            $c .= chr(0xE0 | (($val / 64) / 64));
            $c .= chr(0x80 | (($val / 64) % 64));
            $c .= chr(0x80 | ($val % 64));
            //echot($c);
        }
        $utf8string .= $c;
    }
    return $utf8string;
}

// end func



/*
 * 将utf8编码的字符串编码为unicode 码型，等同escape
 * 之所以只接受utf8码，因为只有utf8码和unicode之间有公式转换，其他的编码都得查码表来转换。
 * 不知道查找utf8码的正则是否完全正确。迷茫ing
 * 虽然调用utf2ucs对每个字符进行码值计算。效率过低。然而，代码清晰，要是把那个计算过程嵌入。
 * 代码就不太容易阅读了。
 */

function utf8Escape($str) {
    preg_match_all("/[\xC0-\xE0].|[\xE0-\xF0]..|[\x01-\x7f]+/", $str, $r);
    //prt($r);
    $ar = $r[0];
    foreach ($ar as $k => $v) {
        $ord = ord($v[0]);
        if ($ord <= 0x7F)
            $ar[$k] = rawurlencode($v);
        elseif ($ord < 0xE0) { //双字节utf8码
            $ar[$k] = "%u" . utf2ucs($v);
        } elseif ($ord < 0xF0) { //三字节utf8码
            $ar[$k] = "%u" . utf2ucs($v);
        }
    }//foreach
    return join("", $ar);
}

/**
 *
 * 把utf8编码字符转为ucs-2编码
 * 参数 utf8编码的字符。
 * 返回 该字符的unicode码值。知道了码值，你就可以使用chr将字符弄出来了。
 *
 *  原理：unicode转为utf-8码的算法是。头部固定位或。
  该过程的逆向算法就是这个函数了，头部固定位反位与。
 */
function utf2ucs($str) {
    $n = strlen($str);
    if ($n = 3) {
        $highCode = ord($str[0]);
        $midCode = ord($str[1]);
        $lowCode = ord($str[2]);
        $a = 0x1F & $highCode;
        $b = 0x7F & $midCode;
        $c = 0x7F & $lowCode;
        $ucsCode = (64 * $a + $b) * 64 + $c;
    } elseif ($n == 2) {
        $highCode = ord($str[0]);
        $lowCode = ord($str[1]);
        $a = 0x3F & $highCode;  //0x3F是0xC0的补数
        $b = 0x7F & $lowCode;  //0x7F是0x80的补数
        $ucsCode = 64 * $a + $b;
    } elseif ($n == 1) {
        $ucscode = ord($str);
    }
    return dechex($ucsCode);
}

/*
 * 用处 ：此函数用来逆转javascript的escape函数编码后的字符。
 * 关键的正则查找我不知道有没有问题.
 * 参数：javascript编码过的字符串。
 * 如：unicodeToUtf8("%u5927")= 大
 * 2005-12-10
 *
 */

function phpUnescape($escstr) {
    preg_match_all("/%u[0-9A-Za-z]{4}|%.{2}|[0-9a-zA-Z.+-_]+/", $escstr, $matches); //prt($matches);
    $ar = &$matches[0];
    $c = "";
    foreach ($ar as $val) {
        if (substr($val, 0, 1) != "%") { //如果是字母数字+-_.的ascii码
            $c .=$val;
        } elseif (substr($val, 1, 1) != "u") { //如果是非字母数字+-_.的ascii码
            $x = hexdec(substr($val, 1, 2));
            $c .=chr($x);
        } else { //如果是大于0xFF的码
            $val = intval(substr($val, 2), 16);
            if ($val < 0x7F) {        // 0000-007F
                $c .= chr($val);
            } elseif ($val < 0x800) { // 0080-0800
                $c .= chr(0xC0 | ($val / 64));
                $c .= chr(0x80 | ($val % 64));
            } else {                // 0800-FFFF
                $c .= chr(0xE0 | (($val / 64) / 64));
                $c .= chr(0x80 | (($val / 64) % 64));
                $c .= chr(0x80 | ($val % 64));
            }
        }
    }
    return $c;
}
