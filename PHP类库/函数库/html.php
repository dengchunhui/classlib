<?php

function back($word) {
    echo "<script>";
    echo "alert('$word');";
    echo "history.back();";
    echo "</script>";
}

function to_url($word, $url) {//这个是页面跳转函数，如：to_url("添加成功！","index.php")
    echo "<script>";
    echo "alert('$word');";
    echo "self.location='$url';";
    echo "</script>";
}

function checked($check, $boolC) {//选定函数，用于单选按钮。
    if ((int) ($check) == $boolC) {
        echo "checked=\"checked\"";
    }
}