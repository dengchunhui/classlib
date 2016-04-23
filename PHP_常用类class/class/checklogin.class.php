<?php

class checklogin {

    var $name;
    var $pwd;

    function __construct($username, $password) {
        $this->name = $username;
        $this->pwd = $password;
    }

    function checkinput() {
        global $db;
        $sql = "select * from tb_manager where name='$this->name' and pwd='$this->pwd'";
        $res = $db->query($sql);
        $info = $db->fetch_array($res);
        if ($info['name'] == $this->name and $info['pwd'] == $this->pwd) {
            $_SESSION[admin_name] = $info[name];
            $_SESSION[pwd] = $info[pwd];
            echo "<script>alert('登录成功！);window.location.href='index.php';</script>";
        } else {
            echo "<script language='javascript'>alert('登录失败！');history.back();</script>";
            exit;
        }
    }

}
?>

