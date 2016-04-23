<?php

/*
  一、引论

  PHP,一门最近几年兴起的web设计脚本语言,由于它的强大和可伸缩性,近几年来得到长足的发展,php相比传统的asp网站,在速度上有绝对的优势,想mssql转6万条数据php如需要40秒,asp不下2分钟.但是,由于网站的数据越来越多,我们渴求能更快速的调用数据,不必要每次都从数据库掉,我们可以从其他的地方,比方一个文件,或者某个内存地址,这就是php的缓存技术,也就是Cache技术.

  二、分析深入

  一般来说,缓存的目的是把数据放在一个地方让访问的更快点,毫无疑问,内存是最快的,但是,几百M的数据能往内存放么?这不现实,当然,有的时候临时放如服务器缓存,如ob_start()这个缓存页面开启的话在发送文件头之前页面内容都被缓存在内存中,知道等页面输出自动清楚或者等待 ob_get_contents的返回,[或者被ob_end_clean显示的清除,这在静态页面的生成中能很好的利用,在模板中能得到很好的体现,我的这篇文章深入的讨论了:
  谈PHP生成静态页面,这是一种方式,但这是临时性的,不是解决我们问题的好方法.

  另外,在asp中有一对象application,可以保存公用的参数,这也算点缓存,但在php,我至今没看到开发者产出这种对象,的确,没必要.asp.net的页面缓存技术就用的是viewstate,而cache就是文件关联,(不一定准确),文件被修改,更新缓存,文件没被修改而且不超时(注释1),就读取缓存,返回结果,就是这个思路,看看这个源码:

  www.444p.com

  PHP代码
 */

class cache {
    /* Class Name: cache
      Des cription: control to cache data,$cache_out_time is a array to save cache date time out.
      Version: 1.0
      Author: 老农 cjjer
      Last modify:2006-2-26
      Author URL:http://www.cjjer.com
     */

    private $cache_dir;
    private $expireTime = 180; //缓存的时间是 60 秒   

    function __construct($cache_dirname) {
        if (!@is_dir($cache_dirname)) {
            if (!@mkdir($cache_dirname, 0777)) {
                $this->warn('缓存文件不存在而且不能创建,需要手动创建.');
                return false;
            }
        }
        $this->cache_dir = $cache_dirname;
    }

    function __destruct() {
        echo 'Cache class bye.';
    }

    function get_url() {
        if (!isset($_SERVER['REQUEST_URI'])) {
            $url = $_SERVER['REQUEST_URI'];
        } else {
            $url = $_SERVER['s cript_NAME'];
            $url .= (!emptyempty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
        }

        return $url;
    }

    function warn($errorstring) {
        echo "<b><font color='red'>发生错误:<pre>" . $errorstring . "</pre></font></b>";
    }

    function cache_page($pageurl, $pagedata) {
        if (!$fso = fopen($pageurl, 'w')) {
            $this->warns('无法打开缓存文件.'); //trigger_error   
            return false;
            if (!flock($fso, LOCK_EX)) {//LOCK_NB,排它型锁定   
                $this->warns('无法锁定缓存文件.'); //trigger_error   
                return false;
            }
            if (!fwrite($fso, $pagedata)) {//写入字节流,serialize写入其他格式   
                $this->warns('无法写入缓存文件.'); //trigger_error   
                return false;
            }
            flock($fso, LOCK_UN); //释放锁定   
            fclose($fso);
            return true;
        }

        function display_cache($cacheFile) {
            if (!file_exists($cacheFile)) {
                $this->warn('无法读取缓存文件.'); //trigger_error   
                return false;
            }
            echo '读取缓存文件:' . $cacheFile;
            //return unserialize(file_get_contents($cacheFile));   
            $fso = fopen($cacheFile, 'r');
            $data = fread($fso, filesize($cacheFile));
            fclose($fso);
            return $data;
        }

        function readData($cacheFile = 'default_cache.txt') {
            $cacheFile = $this->cache_dir . "/" . $cacheFile;
            if (file_exists($cacheFile) & filemtime($cacheFile) > (time() - $this->expireTime)) {
                $data = $this->display_cache($cacheFile);
            } else {
                $data = "from here wo can get it from mysql database,update time is <b>" . date('l dS \of F Y h:i:s A') . "</b>,过期时间是:" . date('l dS \of F Y h:i:s A', time() + $this->expireTime) . "----------";
                $this->cache_page($cacheFile, $data);
            }
            return $data;
        }

        /*
          下面我打断这个代码逐行解释. php学习之家http://www.444p.com

          三、程序透析

          这个缓存类(类没什么好怕的.请继续看)名称是cache,有2个属性:

          private $cache_dir;
          private $expireTime=180;
          $cache_dir是缓存文件所放的相对网站目录的父目录, $expireTime(注释一)是我们缓存的数据过期的时间,主要是这个思路:
          当数据或者文件被加载的时候,先判断缓存文件存在不,返回false ,文件最后修改时间和缓存的时间和比当前时间大不,大的话说明缓存还没到期,小的话返回false,当返回false的时候,读取原始数据,写入缓存文件中,返回数据.,

          接着看程序: php学习之家http://www.444p.com
         */

        function __construct($cache_dirname) {
            if (!@is_dir($cache_dirname)) {
                if (!@mkdir($cache_dirname, 0777)) {
                    $this->warn('缓存文件不存在而且不能创建,需要手动创建.');
                    return false;
                }
            }
            $this->cache_dir = $cache_dirname;
        }

        /* 当类第一次被实例的时候构造默认函数带参数缓存文件名称,如文件不存在,创建一个有编辑权限的文件夹,
          创建失败的时候抛出异常.然后把cache类的 $cache_dir属性设置为这个文件夹名称,我们的所有缓存文件都是在这个
          文件夹下面的. php学习之家
         */

        function __destruct() {
            echo 'Cache class bye.';
        }

//这是class类的析构函数,为了演示,我们输出一个字符串表示我们释放cache类资源成功. php学习之家

        function warn($errorstring) {
            echo "<b><font color='red'>发生错误:<pre>" . $errorstring . "</pre></font></b>";
        }

        /*
          这个方法输出错误信息.
          php学习之家
         */

        function get_url() {
            if (!isset($_SERVER['REQUEST_URI'])) {
                $url = $_SERVER['REQUEST_URI'];
            } else {
                $url = $_SERVER['s cript_NAME'];
                $url .= (!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : '';
            }

            return $url;
        }

        /*
          这个方法返回当前url的信息,这是我看国外很多人的cms系统这样做,主要是缓存x.php?page=1,x.php?page=2,
          等这种文件的,这里列出是为了扩展的这个cache类功能的.

          www.444p.com
         */

        function cache_page($pageurl, $pagedata) {
            if (!$fso = fopen($pageurl, 'w')) {
                $this->warns('无法打开缓存文件.'); //trigger_error
                return false;
            }
            if (!flock($fso, LOCK_EX)) {//LOCK_NB,排它型锁定
                $this->warns('无法锁定缓存文件.'); //trigger_error
                return false;
            }
            if (!fwrite($fso, $pagedata)) {//写入字节流,serialize写入其他格式
                $this->warns('无法写入缓存文件.'); //trigger_error
                return false;
            }
            flock($fso, LOCK_UN); //释放锁定
            fclose($fso);
            return true;
        }

        /*
          cache_page方法分别传入的是缓存的文件名称和数据,这是把数据写到文件里的方法,先用fopen打开文件,
          然后调用句柄锁定这个文件,然后用 fwrite写入文件,最后释放这个句柄,任何一步发生错误将抛出错误.
          您可能看到这个注释写入字节流,serialize写入其他格式,顺便一提的是如果我们要把一个数组,
          (可以从MySQL数据库里面select查询除了的结果)用 serialize函数写入,用unserialize读取到原来的类型. php学习之家
         */

        function display_cache($cacheFile) {
            if (!file_exists($cacheFile)) {
                $this->warn('无法读取缓存文件.'); //trigger_error
                return false;
            }
            echo '读取缓存文件:' . $cacheFile;
//return unserialize(file_get_contents($cacheFile));
            $fso = fopen($cacheFile, 'r');
            $data = fread($fso, filesize($cacheFile));
            fclose($fso);
            return $data;
        }

        /*
          这是由文件名称读取缓存的方法,直接打开文件,读取全部,如果文件不存在的或者无法读取的话返回false,
          当然,你感到不人性的话,可以重新生成缓存.
         */

        function readData($cacheFile = 'default_cache.txt') {
            $cacheFile = $this->cache_dir . "/" . $cacheFile;
            if (file_exists($cacheFile) && filemtime($cacheFile) > (time() - $this->expireTime)) {
                $data = $this->display_cache($cacheFile);
            } else {
                $data = "from here wo can get it from mysql database,update time is <b>" . date('l dS \of F Y h:i:s A') . "</b>,过期时间是:" . date('l dS \of F Y h:i:s A', time() + $this->expireTime) . "----------";
                $this->cache_page($cacheFile, $data);
            }
            return $data;
        }

        /*
          这个函数是我们调用的方法,可以写成接口的方法,由传入参数判断文件存在不,文件最后修改时间+expireTime的时间是不是过了当前时间(大于的话说明没有过期),如果文件不存在或者已经过期,重新加载原始数据,这里,为了简单期间,我们是直接源是字符串,您可以把cache类继承某类,取到数据库的数据.(注释2) php学习之家

          四、补充说明,结语
          注释一 :这个缓存的时间您可以自己调,可以根据时间情况读取数组,xml,缓存等,请按照您的方便,值得一提的是缓存的时间(也就是缓存的key)也用缓存控制,.这在cms系统中被广泛使用,他们把要更新的key放在缓存中,非常容易控制全战.
          php学习之家

          注释二: php5开始支持类继承,这是让人兴奋的,把网站全局休息写在一个配置的类里面,再写与数据层交互的类(如与MySQL交互的类),我们的这个cache类继承数据交互的类,可以非常容易的读取数据库,这是外话,此处不再展开,有时间和大家详谈.

          特别说明,这个类文件针对的php5以上版本,其他版本的请不要使用类.
         */
?>
