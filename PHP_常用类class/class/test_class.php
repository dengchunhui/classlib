<?php

function __autoload($class_name) {
    include_once $class_name . ".class.php";
}

$numq = "SELECT * FROM `test`";
$db = new database();
$res = $db->execute($numq); //总数据条数
$nums = $db->num_rows($res);
$pagesize = 3;
if (!empty($_GET['pagesize'])) {
    $pagesize = $_GET['pagesize'];
}
$page = new page($pagesize, $nums);

//$page->show_page_way_1();
$page->show_page_way_3();
$page->show_page_result();
?>
