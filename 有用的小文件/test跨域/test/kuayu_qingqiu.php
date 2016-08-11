
<?php
/**
 * Created by PhpStorm.
 * User: jingqi
 * Date: 2015/9/2
 * Time: 19:55
 */
//在header中加入 允许跨域
echo header("Access-Control-Allow-Origin: *");
//获取callback 会自动生成回调函数的名称
$callback = $_GET['callback' ];
//如果有这个
if ($callback!=null) {
    //输出 json
    echo"{$callback}({'msg':'this is a jquery jsonp test message!'})";
        exit();
    }
else {
    //如果没有 输出错误信息
    echo"{$callback}({'msg':'none callback!'})";
        exit();
    }