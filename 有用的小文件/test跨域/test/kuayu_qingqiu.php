
<?php
/**
 * Created by PhpStorm.
 * User: jingqi
 * Date: 2015/9/2
 * Time: 19:55
 */
//��header�м��� �������
echo header("Access-Control-Allow-Origin: *");
//��ȡcallback ���Զ����ɻص�����������
$callback = $_GET['callback' ];
//��������
if ($callback!=null) {
    //��� json
    echo"{$callback}({'msg':'this is a jquery jsonp test message!'})";
        exit();
    }
else {
    //���û�� ���������Ϣ
    echo"{$callback}({'msg':'none callback!'})";
        exit();
    }