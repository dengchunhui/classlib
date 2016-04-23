<?php

class user {

    var $usertable;

    function get_oneuser($field, $value) {
        $field_array = array("id", "name");  //查询方式
        if (in_array($field, $field_array)) {
            $sql = "SELECT * FROM `$this->usertable` FROM $field='$value'";
            $db = new database;
            $res = $db->execute($sql);
            $obj_user = mysql_fetch_object($res);
            return $obj_user;
        } else
            echo "查询方式不对";
    }

    function get_moreusers() {
        global $db;
        $argnums = func_num_args();
        $argarr = func_get_args();
        switch ($argnums) {
            case 0:
                $sql = "SELECT * FROM `$this->usertable`";
                break;
            case 2:
                $sql = "SELECT * FROM `$this->usertable` WHERE $argarr[0]='$argarr[1]'";
                break;
            case 4:
                $sql = "SELECT * FROM `$this->usertable` WHERE $argarr[0]='$argarr[1]' AND $argarr[2]='$argarr[3]'";
                break;
        }
        //$db=new database;
        $res = $this->execute($sql);
        $obj_arr = array();
        while ($obj = mysql_fetch_object($res)) {
            $obj_arr[] = $obj;
        }
        return $obj_arr;
    }

}

?>