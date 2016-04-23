<?php

/* * *********************************************
  //实例：
  include("config.inc.php"); //数据库连接文件
  $info = new sys_function;
  $test = new show_article;
  $test->set_sql("SELECT `id`,`title`,`dateandtime`,`thetype` FROM `df_company_bringup` ORDER BY `dateandtime` DESC");
  $test->set_filename("display");
  $test->set_morename("morearticle");
  $test->set_icon(" ☆ ");
  $test->set_omitstr("...");
  $test->show_company_bringup();
 * ********************************************* */

/* * **Article List Class*** */

class show_article {
    /*     * **声明*** */

    var $sql, $query, $arr, $article_list;
    var $strlen, $articleline, $dateandtime;
    var $len, $line, $icon, $omitstr, $line_height, $more_name;
    var $year, $month, $day, $datetype;
    var $showmore, $showdate, $showomit, $showclueon, $showicon;

    /*     * **构造函数*** */

    function show_article() {
        $this->set_strlen(40); //设置每行显示字数；
        $this->set_articleline(10); //设置Article显示行数；
        $this->set_showmore(false); //是否显示"更多Article"；
        $this->set_showdate(true); //是否显示Article发布日期；
        $this->set_showomit(true); //字数超过指定字符后是否显示所设置的字符，如"...";
        $this->set_showclueon(true); //是否显示Article提示（鼠标移动到Article标题上面显示的提示）；
        $this->set_open(true); //是否在新窗口打开;
        $this->set_showicon(true); //是否显示Article修改前缀;
        $this->set_lineheight(1.5); //段落行高值;
        $this->set_datetype(1); //设置日期格式，1为2004-10-21 2为2004年10月21日;
    }

    /*     * **设置每行显示字数*** */

    function set_strlen($strlen) {
        $this->len = $strlen;
    }

    /*     * **设置Article显示行数*** */

    function set_articleline($articleline) {
        $this->line = $articleline;
    }

    /*     * **设置查询语句*** */

    function set_sql($sql) {
        $this->sql = $sql;
    }

    /*     * **设置Article标题前缀修饰*** */

    function set_icon($icon) {
        $this->icon = $icon;
    }

    /*     * **是否显示Article标题前缀修饰*** */

    function set_showicon($showicon) {
        $this->showicon = $showicon;
    }

    /*     * **是否打开新窗口*** */

    function set_open($open) {
        $this->open = $open;
    }

    /*     * **设置Article标题长度超过限制后显示的字符*** */

    function set_omitstr($omitstr) {
        $this->omitstr = $omitstr;
    }

    /*     * **设置打开Article的文件名*** */

    function set_filename($filename) {
        $this->filename = $filename;
    }

    /*     * **更多Article页面名称*** */

    function set_morename($more_name) {
        $this->more_name = $more_name;
    }

    /*     * **Article分类名称*** */

    function set_typename($type_name) {
        $this->type_name = $type_name;
    }

    /*     * **是否显示更多Article*** */

    function set_showmore($showmore) {
        $this->showmore = $showmore;
    }

    /*     * **设置日期格式(1:2004-10-21 2:2004年10月21日)*** */

    function set_datetype($datetype) {
        $this->datetype = $datetype;
    }

    /*     * **格式化日期*** */

    function formatdate($dateandtime, $num) {
        list($year, $month, $day) = split("[-]", substr($dateandtime, 0, 10));
        if ($num == 1) {
            return $year . "-" . $month . "-" . $day;
        } else {
            return $year . "年" . $month . "月" . $day . "日";
        }
    }

    /*     * **是否显示Article发布日期*** */

    function set_showdate($showdate) {
        $this->showdate = $showdate;
    }

    /*     * **段落行高值*** */

    function set_lineheight($line_height) {
        $this->line_height = $line_height;
    }

    /*     * **是否显示Article标题长度超过限制后显示的字符*** */

    function set_showomit($showomit) {
        $this->showomit = $showomit;
    }

    /*     * **是否显示鼠标移动到Article上后显示的提示*** */

    function set_showclueon($showclueon) {
        $this->showclueon = $showclueon;
    }

    /*     * **从结果集中取得一行作为枚举数组*** */

    function execute_row($query) {
        return $this->arr = mysql_fetch_row($query);
    }

    /*     * **从结果集中取得一行作为关联数组，或数字数组，或二者兼有*** */

    function execute_array($query) {
        return $this->arr = mysql_fetch_array($query);
    }

    /*     * **将释放所有与结果标识符 result 所关联的内存*** */

    function free_record($query) {
        @mysql_free_result($query);
    }

    /*     * **调用指定Article List*** */

    function show_company_bringup() {
        $n = 1;
        $article_list = "<div id='article_list" . $n . "' style='line-height:" . $this->line_height . "'>";
        $this->query = mysql_query($this->sql);
        while ($this->execute_row($this->query)) {
            if ($this->showicon) {
                $article_list .= $this->icon;
            }
            $article_list .= "<a href='" . $this->filename . ".php?id=" . $this->arr[0] . "'";
            if ($this->showclueon) {
                $article_list .= " title='" . $this->arr[1] . "' ";
            }
            if ($this->open) {
                $article_list .= " target='_blank' ";
            }
            $article_list .= ">";
            if (strlen($this->arr[1]) > $this->len) {
                if ($this->showomit) {
                    $article_list .= substr($this->arr[1], 0, $this->len) . $this->omitstr;
                } else {
                    $article_list .= substr($this->arr[1], 0, $this->len);
                }
            } else {
                $article_list .= $this->arr[1];
            }
            if ($this->showdate) {
                $article_list .= "[" . $this->formatdate($this->arr[2], $this->datetype) . "]";
            }
            $article_list .= "</a><br>\n";
            if ($n == $this->line) {
                break;
            }
            $n++;
        }
        if ($this->showmore) {
            $article_list .= "</div><div id='article_list_more" . $n . "' align='right'><a href='" . $this->more_name . ".php?type=" . $this->type_name . "'>>> >更多</a></div>";
        } else {
            $article_list .= "</div>";
        }
        $this->free_record($this->query);
        print $article_list;
    }

}

?>
