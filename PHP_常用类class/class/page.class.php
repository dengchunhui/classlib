<?php

class page {

    private $pagesize;
    private $lastpage;
    private $totalpages;
    private $nums;
    private $numPage = 1;

    function __construct($page_size, $total_nums) {
        $this->pagesize = $page_size;  //每页显示的数据条数
        $this->nums = $total_nums;  //总的数据条数
        $this->lastpage = ceil($this->nums / $this->pagesize);  //最后一页
        $this->totalpages = ceil($this->nums / $this->pagesize); //总得分页数
        if (!empty($_GET[page])) {
            $this->numPage = $_GET[page];
            if (!is_int($this->numPage))
                $this->numPage = (int) $this->numPage;
            if ($this->numPage < 1)
                $this->numPage = 1;
            if ($this->numPage > $this->lastpage)
                $this->numPage = $this->lastpage;
        }
    }

    function show_page_result() {
        $row_num = (($this->numPage) - 1) * $this->pagesize; //表示每一页从第几条数据开始显示
        $row_num = $row_num . ",";
        $SQL = "SELECT * FROM `test` LIMIT $row_num $this->pagesize";
        $db = new database();
        $query = $db->execute($SQL);
        while ($row = mysql_fetch_array($query)) {
            echo "<b>" . $row[name] . " | " . $row[sex] . "<hr>";
        }
        $db = null;
    }

    function show_page_way_1() { //以"首页 上一页 下一页 尾页"形式显示
        $url = $_SERVER["REQUEST_URI"];
        $url = parse_url($url); //parse_url -- 解析 URL，返回其组成部分,注: 此函数对相对路径的 URL 不起作用。
        $url = $url[path];
        if ($this->nums > $this->pagesize) {  //判断是否满足分页条件
            echo " 共 $this->totalpages 页 当前为第<font color=red><b>$this->numPage</b></font>页 共 $this->nums 条 每页显示 $this->pagesize 条";
            if ($this->numPage == 1) {
                echo " 首页 ";
                echo "上一页 ";
            }
            if ($this->numPage >= 2 && $this->numPage <= $this->lastpage) {
                echo " <a href=$url?page=1>首页</a> ";
                echo "<a href=$url?page=" . ($this->numPage - 1) . ">上一页</a> ";
            }

            if ($this->numPage == $this->lastpage) {
                echo "下一页 ";
                echo "尾页<br>";
            }
            if ($this->numPage >= 1 && $this->numPage < $this->lastpage) {
                echo "<a href=$url?page=" . ($this->numPage + 1) . ">下一页</a> ";
                echo "<a href=$url?page=$this->lastpage>尾页</a><br> ";
            }
        } else
            return;
    }

    function show_page_way_2() {  //以数字形式显示"首页 1 2 3 4 尾页"
        $url = $_SERVER["REQUEST_URI"];
        $url = parse_url($url); //parse_url -- 解析 URL，返回其组成部分,注: 此函数对相对路径的 URL 不起作用。
        $url = $url[path];
        if ($this->nums > $this->pagesize) {
            if ($this->numPage == 1)
                echo "首页";
            else
                echo "<a href=$url?page=1>首页</a>";
            for ($i = 1; $i <= $this->totalpages; $i++) {
                if ($this->numPage == $i) {
                    echo " " . $i . " ";
                } else {
                    echo " <a href=$url?page=$i>$i</a> ";
                }
            }
            if ($this->numPage == $this->lastpage)
                echo "尾页";
            else
                echo "<a href=$url?page=$this->lastpage>尾页</a>";
        }
    }

    function show_page_way_3() {
        global $c_id;
        $url = $_SERVER["REQUEST_URI"];
        $url = parse_url($url); //parse_url -- 解析 URL，返回其组成部分,注: 此函数对相对路径的 URL 不起作用。
        $url = $url[path];
        if ($this->nums > $this->pagesize) {  //判断是否满足分页条件
            if ($c_id) {
                echo "到第<select name='select1' onChange=\"location.href='$url?c_id=$c_id&page='+this.value+'&pagesize=$this->pagesize'\">";
            } else
                echo "到第<select name='select1' onChange=\"location.href='$url?page='+this.value+'&pagesize=$this->pagesize'\">";
            for ($i = 1; $i <= $this->totalpages; $i++)
                echo "<option value='" . $i . "'" . (($this->numPage == $i) ? 'selected' : '') . ">" . $i . "</option>";
            echo "</select>页, 每页显示";
            if ($c_id) {
                echo "<select name=select2 onChange=\"location.href='$url?c_id=$c_id&page=$this->numPage&pagesize='+this.value+''\">";
            } else
                echo "<select name=select2 onChange=\"location.href='$url?page=$this->numPage&pagesize='+this.value+''\">";
            for ($i = 0; $i < 5; $i++) { // 将个数定义为五种选择
                $choice = ($i + 1) * 4;
                echo "<option value='" . $choice . "'" . (($this->pagesize == $choice) ? 'selected' : '') . ">" . $choice . "</option>";
            }
            echo "</select>个";
        } else
            return;  //echo "没有下页了";
    }

}
?>



