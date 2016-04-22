<?php
其他
    isset() 变量是否存在
    boolean empty() 检查变量是否存在，并判断值是否为非空或非0
    void unset() 销毁变量
    header('Content-Type: text/html; charset=utf-8');设置页面编码、跳转、文件流信息
	method_exists($obj, $method) 判断对象的方法是否可用
	file_exists($file)	判断文件是否存在
	function_exists();
	class_exists($class_name);检查类是否已定义，类已经定义，此函数返回 TRUE，否则返回 FALSE。
    gettype();获取数据类型
    //set_magic_quotes_runtime() 0 for off, 1 for on 当遇到反斜杆、单引号，将会自动加上一个反斜杆，保护系统和数据库的安全(5.30起已废止，建议不要使用)
	ini_set();Sets the value of a configuration option
	mt_rand();生成更好的随机数
	flush();ob_flush();刷新输出缓存
	abs();绝对值
	ceil();进一取整
	max();找最大值
	round();对浮点数四舍五入
	base64_encode();使用 MIME base64 对数据进行编码 //base64_decode();解码
	get_meta_tags();从一个文件中提取所有的meta标签content属性，解析工作在</head>处结束。返回一个数组
	$num = 123213.666666;echo sprintf("%.2f", $num);//php保留两位小数并且四舍五入 
	$num = 123213.666666;echo sprintf("%.2f",substr(sprintf("%.4f", $num), 0, -2));  ;//php保留两位小数并且不四舍五入  
	//iframe父子窗口值传递
	$(document.getElementById('my_iframe').contentWindow.document.body).html(html_content);//给子窗口里元素赋值
	$(document.getElementById('my_iframe').contentWindow.document.body).html();//获取子窗口里元素的值
	//1.在父窗口中操作 选中IFRAME中的所有单选钮
	$(window.frames["iframe1"].document).find("input[@type='radio']").attr("checked","true");
	//2.在IFRAME中操作 选中父窗口中的所有单选钮
	$(window.parent.document).find("input[@type='radio']").attr("checked","true");
	//常见形式：
	window.frames["iframe的name值"].document.getElementByIdx_x("iframe中控件的ID").click(); 
	第一、在iframe中查找父页面元素的方法：$('#id', window.parent.document) 
	第二、在父页面中获取iframe中的元素方法：$(this).contents().find("#suggestBox") 
	第三、在iframe中调用父页面中定义

安全
	function strReplace($str)
	{
	  $strResult = $str;
	  if(!get_magic_quotes_gpc())//判断设置是否开启
	  {
		$strResult = addslashes($strResult);//转换sql语句特殊字符
	  }
	  return $strResult;
	}


	function quotes($content)
	{
		//如果magic_quotes_gpc=Off，那么就开始处理
		if (!get_magic_quotes_gpc())
		{
			//判断$content是否为数组
			if (is_array($content))
			{
				//如果$content是数组，那么就处理它的每一个单无
				foreach ($content as $key=>$value)
				{
					$content[$key] = addslashes($value);
				}
			}
			else
			{
				//如果$content不是数组，那么就仅处理一次
				addslashes($content);
			}
		}
		//返回$content
		return $content;
	}

编码转换
	string mb_convert_encoding ( string $str , string $to_encoding [, mixed $from_encoding ] )
	iconv();

时间
！	date_default_timezone_set("PRC");转为东八时区
	date("Y-m-d H:i:s");格式化一个本地时间
	date("Y-m-d H:i:s",time()+3600)；
	time();返回当前的Unix时间戳.秒数
	ini_set('date.timezone', 'PRC'); 改配置文件设东八时区，一般不用
	msec sec microtime() 返回运行毫秒数 和当前秒数
	    以秒返回时间戳 explode(' ', microtime())
	getdate()以关联数组返回详细时间信息
	localtime()以索引数组返回详细时间信息
	strtotime() 将任何英文文本的日期时间描述解析为Unix时间戳


魔术方法
    __construct() 当实例化一个对象的时候，这个对象的这个方法首先被调用。
    __destruct() 当删除一个对象或对象操作终止的时候，调用该方法。
    __get() 当试图读取一个私有的或并不存在的属性的时候被调用。
    __set() 当试图向一个私有的或并不存在的属性写入值的时候被调用。
    __call() 当试图调用一个对象并不存在的方法时，调用该方法。不能完成方法的执行，只完成一个报告。
    __toString() 当打印一个对象的时候被调用，把对象转化为字符串
    __clone() 当对象被克隆时，被调用
	__isset()利用isset在类外面判断私有属性时触发，必须有一个参数
	__unset()利用unset在类外面销毁私有属性时触发，必须有一个参数
	__autoload($classname)在试图使用尚未被定义的类时自动调用。并不是在实例化时执行。

	__sleep()---后面序列化内例子
	__wakeup()

系统常量
    __FILE__ 当前文件名
    __LINE__ 当前行数
    __FUNCTION__ 当前函数名
    __CLASS__ 当前类名
    __METHOD__ 当前对象的方法名
    PHP_OS 当前系统
    PHP_VERSION php版本
    DIRECTORY_SEPARATOR 根据系统决定目录的分隔符 /\
    PATH_SEPARATOR 根据系统决定环境变量的目录列表分隔符 ; :
    E_ERROR 1
    E_WARNING 2
    E_PARSE 4
    E_NOTICE 8
    M_PI    3.141592
	超全局变量
    $_SERVER 服务器变量
    $_ENV 执行环境提交至脚本的变量
    $_GET HTTP GET变量
    $_POST HTTP POST变量
    $_REQUEST
    $_FILES HTTP文件上传变量
    $_COOKIE
    $_SESSION
    $GLOBALS

输出
	echo   //Output one or more strings
	print    //Output a string
	print_r()  //打印关于变量的易于理解的信息。
	var_dump()  //打印变量的相关信息
	var_export()  //输出或返回一个变量的字符串表示
	printf("%.1f",$num)  //Output a formatted string
    sprintf()  //Return a formatted string

错误处理
    设变量初值为null

	@1/0

	error_reporting(E_ALL) 显示所有错误
    error_reporting(0)
	trigger_error("Cannot divide by zero", E_USER_ERROR);
	try
	{
		throw new Exception("执行失败");
	}
	catch (Exception $ex)
	{
		echo $ex;
	}

	error_reporting(E_ERROR | E_WARNING | E_PARSE); 用来配置错误信息回报的等级

字符串处理
	string trim("eee ") trim ('ffffe','e')  去掉字符串头尾空格//ltrim 去左 rtrim 去右
	array explode(".", "fff.ff.f") 按指定字符切割 返回数组
	string implode(".", $array)  别名：join   把数组值数据按指定字符连接起来
	array str_split("eeeeeeee",4) 按长度切割字符串
    array split("-","fff-ff-f") 按指定字符切割字符串
	int strlen('ffffffff')  取字符长度
	string substr ( string $string , int $start [, int $length ] )
           substr($a,-2, 2) 截取字符
	int substr_count($text, 'is') 字符串出现的次数
	string strstr($text, 'h') 第一次出现h后的字符串   //别名：strchr
	int strpos($text, 'h') 第一次出现h的位置
	    strrpos();最后一次出现h的位置
	    str_replace('a', 'ttt', $t) 把$t 里的'a'替换为'ttt'
        strtr($t,'is','tom') 把$t中'i'替换成't','s'替换成'o','m'则溢出丢弃
              strtr("hi all, I said hello", array("hello" => "hi")) 把'hello'转换成'hi'
	string md5_file('1.txt',false) 文件数据md5加密
           md5($str);字符串md5加密
	int strcmp(string str1, string str2) 字符串比较
	int strcasecmp(string str1, string str2) 忽略大小写比较
    string str_pad($i, 10, "-=", STR_PAD_LEFT) 在原字符左边补'-='，直到新字符串长度为10
        STR_PAD_RIGHT
        STR_PAD_BOTH
	string str_repeat('1', 5) 重复5个1
    void parse_str('id=11'); echo $id; 将字串符解析为变量
         parse_url();解析完整的URL为关联数组，包含在 URL 中出现的各种组成部分
	string nl2br("foo isn't\n bar") "foo isn't<br> bar" 把换行转成<br>
	string chr( int ascii ) 将ASCII码转化为字符
    int ord('a')  97  将字符转化为ASCII码
	mixed str_word_count( string string [, int format [, string charlist]] )统计字符串内单词个数，以非字母字符分割

	string str_shuffle('abc') 打乱字符串顺序,（？不支持汉字）
	string strrev($str) * 翻转一个字符串
	string strtolower($str) *  将字符串 $str 的字符全部转换为小写的
	string strtoupper($str) *  将字符串 $str 的字符全部转换为大写的
	string ucfirst($str)* 将字符串 $str 的第一个单词的首字母变为大写。
	string ucwords($str)* 将字符串 $str 的每个单词的首字母变为大写。

	string addslashes("I'm") I\'m 使用反斜线引用字符 这些字符是单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）一般系统默认打开，使用前先判断是否打开(get_magic_quotes_gpc())
	string stripcslashes("I\'m")I'm将用addslashes()函数处理后的字符串返回原样
	       strip_tags("<p>tt</p>", '<p>') 去除html、xml、php标记，第二个参数用来保留标记
	string urlencode(string str)替换所有非字母数字的字符变为%后跟两位16进制，空格变为+
	string urldecode(string str)对已编码的URL地址解码

	string htmlspecialchars("<a href='test'>Test</a>", ENT_QUOTES) 转换特殊字符为HTML字符编码
		&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;
		ENT_COMPAT –对双引号进行编码，不对单引号进行编码
		ENT_QUOTES –对单引号和双引号进行编码
		ENT_NOQUOTES –不对单引号或双引号进行编码
	string htmlentities('<p>ff</p>', ENT_QUOTES) 转换特殊字符为HTML字符编码，中文会转成乱码

	array preg_grep("/^(\d+)?\.\d+$/", array(11.2,11,11.2)) 匹配数据
	array preg_split ("/[\s,]+/", "hypertext language,programming"); 按指定的字符切割
	array pathinfo(string path [, int options]) 返回文件路径的信息
	string basename ( string path [, string suffix] ) 返回路径中的文件名部分
	string dirname ( string path )  $_SERVER[PHP_SELF]  返回路径中的目录部分

数组处理
	int count( mixed var [, int mode] ) 别名：sizeof() 取数组长度
	array explode(".", "fff.ff.f") 按指定字符切割字符串，返回为数组
	string implode(".", $array)  别名：join把数组值数据按指定字符连接起来，返回为字符串
	array range(0, 6, 2) 返回数组 array(0,2,4,6) 第一个参数为起使数，第二个参数为结束数，第三个参数为数据增加步长组建数组
	int array_push($a, "3", 1) 把'3'、'1'压入$a,将一个或多个单元压入数组的末尾（入栈）,第二个参数开始就是压入的数据
	void unset ( mixed var [, mixed var [, ...]] )
	array array_pad ($a, 5, 's')用's'将数组填补到指定长度，作为一个新数组
	bool shuffle ( array $array )  将数组打乱
	mixed array_rand ( array input [, int num_req])从数组中随机取出一个或多个单元的索引或键名作为一个新数组
	array array_count_values ( array input )统计数组中所有的值出现的次数
	array array_combine ( array keys, array values) 创建一个数组，用一个数组的值作为其键名，另一个数组的值作为其值
	bool array_key_exists ( mixed key, array search )检查给定的键名或索引是否存在于数组中
	mixed array_search ( mixed needle, array haystack [, bool strict] )在数组中搜索给定的值，如果成功则返回相应的键名,失败返回布尔值
	bool is_array ( mixed var )判断是否是数组
	bool in_array ( mixed needle, array haystack [, bool strict] )检查数组中是否存在某个值
	int array_sum ( array array )计算数组中所有值的和
	array array_unique ( array array )移除数组中重复的值，并返回新数组。键名保留先遇到的值的。
	mixed reset ( array &array )将数组的内部指针指向第一个单元
	mixed current ( array &array )返回数组中的当前单元
	mixed next ( array &array )下一个
	mixed prev ( array &array )前一个
	mixed end ( array &array )尾部
	mixed key ( array &array )从关联数组中取得当前单元键名
	array array_keys ( array input [, mixed search_value [, bool strict]] ) 返回数组中所有的键名
	array array_values ( array input ) 返回数组中所有的值
	bool print_r ( mixed expression [, bool return] )打印关于变量的易于理解的信息
	void var_dump ( mixed expression [, mixed expression [, ...]] )显示关于一个或多个表达式的结构信息，包括表达式的类型与值。数组将递归展开值，通过缩进显示其结构。
	int array_unshift ( array &array, mixed var [, mixed ...] )在数组开头插入一个或多个单元
	mixed array_shift ( array &array )将数组开头的一个单元移出数组
	mixed array_pop ( array &array )将数组最后一个单元弹出（出栈）
	array array_splice ( array $input, int offset [, int length [, array replacement]] ) 把数组中的一部分去掉并用其它值取代
	array array_merge ( array array1 [, array array2 [, array ...]] )合并一个或多个数组
	array array_flip ( array trans )交换数组中的键和值，成为一个新数组
	int extract( array var_array [, int extract_type [, string prefix]] ) 从数组中将变量导入到当前的符号表(对待非法／数字和冲突的键名的方法将根据 extract_type 参数决定)
	array compact ( mixed varname [, mixed ...] ) 建立一个数组，包括变量名和它们的值。接受可变的参数数目。每个参数可以是一个包括变量名的字符串或者是一个包含变量名的数组，该数组中还可以包含其它单元内容为变量名的数组， compact() 可以递归处理。
	bool sort ( array &array [, int sort_flags] )对数组单元从最低到最高重新安排
	bool natsort($a)	用“自然排序”算法对数组排序
    bool rsort ( array &array [, int sort_flags] )对数组进行逆向排序（最高到最低）
	bool asort ( array &array [, int sort_flags] )对数组进行排序并保持索引关系
	bool arsort ( array &array [, int sort_flags] ) 对数组进行逆向排序并保持索引关系
	bool ksort ( array &array [, int sort_flags] )对数组按照键名排序
	bool krsort ( array &array [, int sort_flags] )对数组按照键名逆向排序
	array array_filter ( array input [, callback callback] ) 用回调函数过滤数组中的单元
？	bool array_walk( array &array, callback funcname [, mixed userdata] ) 对数组中的每个成员应用用户函数
？	array array_map( callback callback, array arr1 [, array ...] )将回调函数作用到给定数组的单元上
	array array_fill( int start_index, int num, mixed value ) 用给定的值填充数组
        array_fill(5, 3, 'a')-->array(5=>'a',6=>'a',7=>'a')
	array array_chunk( array input, int size [, bool preserve_keys] )将一个数组分割成多个
	array array_change_key_case($arr,CASE_UPPER/CASE_LOWER);将目标数组索引值中所有字符串索引的英文字母转换为全大写/小写

smarty
	模板引擎将不分析:原码输出
		<!--{literal}-->
		<script>
			function t() {
			}
		</script>
		<!--{/literal}-->
	读取配置文件 config_load
		<!--{config_load file="config.s"}-->
		<!--{#site_url#}-->
		<!--{$smarty.config.site_url}-->
	引入文件
		<!--{include file="index2.html"}-->
		<!--{include_php file="/path/to/load_nav.php"}--> $trusted_dir 指定目录下的文件
	捕获模板输出的数据
		<!--{capture name='eee'}-->
			fffffffff
		<!--{/capture}-->
		<!--{$smarty.capture.eee}-->
	循环
		<{section name=loop loop=$News_IN}>
			<{$News_IN[loop].NewsID}>
		<{/section}>

		<!--{section name=t loop=$data}-->
			<tr>
				<td><!--{$data[t].username}--></td>
			</tr>
		<!--{/section}-->

		<{foreach from=$newsArray item=newsID key=k}>
			新闻编号：<{$newsID.newsID}><br>
			新闻内容：<{$newsID.newsTitle}><br><hr>
		<{/foreach}>
	判断
		<!--{if true}-->
			1111
		<!--{else}-->
			22222222
		<!--{/if}-->
	时间
		{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
		%Y年%m月%d日 乱码
			<!--{$smarty.now|date_format:"%Y年%m月%d日 %H时%M分%S秒"}-->
			修改插件：plugins/modifier.date_format.php
			$format = mb_convert_encoding($format,'gbk','utf-8');
			return mb_convert_encoding(strftime($format, $timestamp),'utf-8','gbk');

	局部不缓存
		html:
			<!--{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}-->
			<!--{cacheless  a="aaa" b="bbbb"}-->
				<!--{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}-->
			<!--{/cacheless}-->
		php:
			$smarty->register_block('cacheless', 'smarty_block_dynamic', false);//true:缓存，false:不缓存
			function smarty_block_dynamic($param, $content, &$smarty)
			{
				return $content;
			}

		php:
			function insert_kk()//方法名前必须有"insert"
			{
				return date('Y-m-d H:i:s');
			}
		html:
			<!--{insert name="kk"}-->
	自定义方法
		注册方法
			php
				$smarty->register_function('test1', 'test');
				function test($p)
				{
					return 'ffffffffff';
				}
			html:
				<!--{test1 name="ff"}-->
		------------------------------------------------
		方法自定义
			插件文件方式定义方法
				function.test.php 文件存在plugins目录下，方法名：smarty_function_test($params, &$smarty)
					function smarty_function_test($params, &$smarty)
					{
						return 'fff';
					}
			html调用：
				<!--{test name='aa' p='ff'}-->
		----------------------------------------------------
		插入方法
			插件文件：insert.kk.php文件存于plugins目录下
				function smarty_insert_kk()
				{
					return date('Y-m-d H:i:s');
				}
			php:
				function insert_kk()//方法名前必须有"insert"
				{
					return date('Y-m-d H:i:s');
				}
			html:
				<!--{insert name="kk"}-->
		-------------------------------------------------
		管道符自定义方法
			插件文件方式定义方法
				modifier.test.php 文件存在于plugins目录下，方法名: function smarty_modifier_test($str, $str2)
					function smarty_modifier_test($str, $str2)
					{
						return $str.$str2;
					}
			html调用：
				<!--{'ff'|test:'tt'}-->

			php:
				function eee($a)
				{
					return 'ffffffffffffff';
				}
			html:
				<!--{''|@eee}-->
	if语句
		eq相等，
		ne、neq不相等，
		gt大于
		gte、ge大于等于，
		lte、le 小于等于，
		not非， mod求模。
		is [not] div by是否能被某数整除，
		is [not] even是否为偶数，
		$a is [not] even by $b 即($a / $b) % 2 == 0
		is [not] odd是否为奇
		$a is not odd by $b即($a / $b) % 2 != 0

XML
    sax
		xml:
			<--?xml version="1.0" encoding="utf-8"?-->
			<books>
			  <book>
				  <author>Jack Herrington</author>
				  <title>PHP Hacks</title>
				  <publisher>O'Reilly</publisher>
			  </book>
			  <book>
				  <author>Jack Herrington</author>
				  <title>Podcasting Hacks</title>
				  <publisher>O'Reilly</publisher>
			  </book>
			  <book>
				  <author>作者</author>
				  <title>标题</title>
				  <publisher>出版者</publisher>
			  </book>
			</books>
		php:
		  $g_books = array();
		  $g_elem = null;

		  function startElement( $parser, $name, $attrs )
		  {
			  global $g_books, $g_elem;
			  if ( $name == 'BOOK' ) $g_books []= array();
			  $g_elem = $name;
		  }

		  function endElement( $parser, $name )
		  {
			  global $g_elem;
			  $g_elem = null;
		  }

		  function textData( $parser, $text )
		  {
			  global $g_books, $g_elem;
			  if ( $g_elem == 'AUTHOR' ||
				  $g_elem == 'PUBLISHER' ||
				  $g_elem == 'TITLE' )
			  {
				$g_books[ count( $g_books ) - 1 ][ $g_elem ] = $text;
			  }
		  }

		  $parser = xml_parser_create();

		  xml_set_element_handler( $parser, "startElement", "endElement" );
		  xml_set_character_data_handler( $parser, "textData" );

		  $f = fopen( '1.xml', 'r' );

		  while($data = fread( $f, 4096 ))
		  {
			xml_parse( $parser, $data );
		  }

		  xml_parser_free( $parser );

		  foreach( $g_books as $book )
		  {
			  echo $book['TITLE']." - ".$book['AUTHOR']." - ";
			  echo $book['PUBLISHER']."<br>";
		  }
    DomDocument()
		xml:
			<--?xml version="1.0" encoding="utf-8"?-->
			<books>
			  <book>
				  <author>Jack Herrington</author>
				  <title>PHP Hacks</title>
				  <publisher>O'Reilly</publisher>
			  </book>
			  <book>
				  <author>Jack Herrington</author>
				  <title>Podcasting Hacks</title>
				  <publisher>O'Reilly</publisher>
			  </book>
			  <book>
				  <author>作者</author>
				  <title>标题</title>
				  <publisher>出版者</publisher>
			  </book>
			</books>
		php读取:
			  $doc = new DOMDocument();
			  $doc->load( "1.xml");

			  $books = $doc->getElementsByTagName( "book" );
			  foreach( $books as $book )
			  {
				  $authors = $book->getElementsByTagName( "author" );
				  $author = $authors->item(0)->nodeValue;

				  $publishers = $book->getElementsByTagName( "publisher" );
				  $publisher = $publishers->item(0)->nodeValue;

				  $titles = $book->getElementsByTagName( "title" );
				  $title = $titles->item(0)->nodeValue;

				  echo "$title - $author - $publisher<br>";
			  }
		php生成:
			$books = array();
			$books [] = array(
				'title' => 'PHP Hacks',
				'author' => 'Jack Herrington',
				'publisher' => "O'Reilly"
				);
			$books [] = array(
				'title' => 'Podcasting Hacks',
				'author' => 'Jack Herrington',
				'publisher' => "O'Reilly"
				);

			$doc = new DOMDocument();
			$doc->formatOutput = true;

			$r = $doc->createElement( "books" );
			$doc->appendChild( $r );

			foreach( $books as $book )
			{
				$b = $doc->createElement( "book" );
				$author = $doc->createElement( "author" );
				$author->appendChild($doc->createTextNode( $book['author'] ));
				$b->appendChild( $author );

				$title = $doc->createElement( "title" );
				$title->appendChild($doc->createTextNode( $book['title'] ));
				$b->appendChild( $title );

				$publisher = $doc->createElement( "publisher" );
				$publisher->appendChild($doc->createTextNode( $book['publisher'] ));
				$b->appendChild( $publisher );
				$r->appendChild( $b );
			}

			echo $doc->saveXML();
			echo $doc->save('222.xml');
    SimpleXML
		xml:
		<books>
		  <book>
			  <author>Jack Herrington</author>
			  <title>PHP Hacks</title>
			  <publisher>O'Reilly</publisher>
		  </book>
		</books>
		php:
		$xml = new SimpleXMLElement('1.xml', NULL, TRUE);
		echo $xml->book[0]->author."___".$xml->book[0]->title."___".$xml->book[0]->publisher;

正则
	ereg系列的正则表达式不需要定届符,preg系列的才需要,并且定界符可以自己选择,只有前后一对就行,比如我们一般使用/符号,但是如果里面有/需要匹配那么就需要使用\/来表示,当/需要出现多次的时候,这样就不方便,我们就可以使用其他的定界符,比如|


	正则特殊字符
		. \ + * ? [ ^ ] $ ( ) { } = ! < > | :
    由原子(普通字符，如英文字符)、
    元字符(有特殊功用的字符)
    模式修正字符
    一个正则表达式中，至少包含一个原子

    全部符号解释
        \  将下一个字符标记为一个特殊字符、或一个原义字符、或一个 向后引用、或一个八进制转义符。例如，'n' 匹配字符 "n"。'\n' 匹配一个换行符。序列 '\\' 匹配 "\" 而 "\(" 则匹配 "("。
        ^  匹配输入字符串的开始位置。如果设置了 RegExp 对象的 Multiline 属性，^ 也匹配 '\n' 或 '\r' 之后的位置。
        $  匹配输入字符串的结束位置。如果设置了RegExp 对象的 Multiline 属性，$ 也匹配 '\n' 或 '\r' 之前的位置。
        *  匹配前面的子表达式零次或多次。例如，zo* 能匹配 "z" 以及 "zoo"。* 等价于{0,}。
        +  匹配前面的子表达式一次或多次。例如，'zo+' 能匹配 "zo" 以及 "zoo"，但不能匹配 "z"。+ 等价于 {1,}。
        ?  匹配前面的子表达式零次或一次。例如，"do(es)?" 可以匹配 "do" 或 "does" 中的"do" 。? 等价于 {0,1}。
        {n}  n 是一个非负整数。匹配确定的 n 次。例如，'o{2}' 不能匹配 "Bob" 中的 'o'，但是能匹配 "food" 中的两个 o。
        {n,}  n 是一个非负整数。至少匹配n 次。例如，'o{2,}' 不能匹配 "Bob" 中的 'o'，但能匹配 "foooood" 中的所有 o。'o{1,}' 等价于 'o+'。'o{0,}' 则等价于 'o*'。
        {n,m}  m 和 n 均为非负整数，其中n <= m。最少匹配 n 次且最多匹配 m 次。例如，"o{1,3}" 将匹配 "fooooood" 中的前三个 o。'o{0,1}' 等价于 'o?'。请注意在逗号和两个数之间不能有空格。
        ?  当该字符紧跟在任何一个其他限制符 (*, +, ?, {n}, {n,}, {n,m}) 后面时，匹配模式是非贪婪的。非贪婪模式尽可能少的匹配所搜索的字符串，而默认的贪婪模式则尽可能多的匹配所搜索的字符串。例如，对于字符串 "oooo"，'o+?' 将匹配单个 "o"，而 'o+' 将匹配所有 'o'。
        .  匹配除 "\n" 之外的任何单个字符。要匹配包括 '\n' 在内的任何字符，请使用象 '[.\n]' 的模式。
        (pattern)  匹配 pattern 并获取这一匹配。所获取的匹配可以从产生的 Matches 集合得到，在VBScript 中使用 SubMatches 集合，在JScript 中则使用 $0…$9 属性。要匹配圆括号字符，请使用 '\(' 或 '\)'。
        (?:pattern)  匹配 pattern 但不获取匹配结果，也就是说这是一个非获取匹配，不进行存储供以后使用。这在使用 "或" 字符 (|) 来组合一个模式的各个部分是很有用。例如， 'industr(?:y|ies) 就是一个比 'industry|industries' 更简略的表达式。
        (?=pattern)  正向预查，在任何匹配 pattern 的字符串开始处匹配查找字符串。这是一个非获取匹配，也就是说，该匹配不需要获取供以后使用。例如，'Windows (?=95|98|NT|2000)' 能匹配 "Windows 2000" 中的 "Windows" ，但不能匹配 "Windows 3.1" 中的 "Windows"。预查不消耗字符，也就是说，在一个匹配发生后，在最后一次匹配之后立即开始下一次匹配的搜索，而不是从包含预查的字符之后开始。
        (?!pattern)  负向预查，在任何不匹配 pattern 的字符串开始处匹配查找字符串。这是一个非获取匹配，也就是说，该匹配不需要获取供以后使用。例如'Windows (?!95|98|NT|2000)' 能匹配 "Windows 3.1" 中的 "Windows"，但不能匹配 "Windows 2000" 中的 "Windows"。预查不消耗字符，也就是说，在一个匹配发生后，在最后一次匹配之后立即开始下一次匹配的搜索，而不是从包含预查的字符之后开始
        x|y  匹配 x 或 y。例如，'z|food' 能匹配 "z" 或 "food"。'(z|f)ood' 则匹配 "zood" 或 "food"。
        [xyz]  字符集合。匹配所包含的任意一个字符。例如， '[abc]' 可以匹配 "plain" 中的 'a'。
        [^xyz]  负值字符集合。匹配未包含的任意字符。例如， '[^abc]' 可以匹配 "plain" 中的'p'。
        [a-z]  字符范围。匹配指定范围内的任意字符。例如，'[a-z]' 可以匹配 'a' 到 'z' 范围内的任意小写字母字符。
        [^a-z]  负值字符范围。匹配任何不在指定范围内的任意字符。例如，'[^a-z]' 可以匹配任何不在 'a' 到 'z' 范围内的任意字符。
        \b  匹配一个单词边界，也就是指单词和空格间的位置。例如， 'er\b' 可以匹配"never" 中的 'er'，但不能匹配 "verb" 中的 'er'。
        \B  匹配非单词边界。'er\B' 能匹配 "verb" 中的 'er'，但不能匹配 "never" 中的 'er'。
        \cx  匹配由 x 指明的控制字符。例如， \cM 匹配一个 Control-M 或回车符。x 的值必须为 A-Z 或 a-z 之一。否则，将 c 视为一个原义的 'c' 字符。
        \d  匹配一个数字字符。等价于 [0-9]。
        \D  匹配一个非数字字符。等价于 [^0-9]。
        \f  匹配一个换页符。等价于 \x0c 和 \cL。
        \n  匹配一个换行符。等价于 \x0a 和 \cJ。
        \r  匹配一个回车符。等价于 \x0d 和 \cM。
        \s  匹配任何空白字符，包括空格、制表符、换页符等等。等价于 [ \f\n\r\t\v]。
        \S  匹配任何非空白字符。等价于 [^ \f\n\r\t\v]。
        \t  匹配一个制表符。等价于 \x09 和 \cI。
        \v  匹配一个垂直制表符。等价于 \x0b 和 \cK。
        \w  匹配包括下划线的任何单词字符。等价于'[A-Za-z0-9_]'。
        \W  匹配任何非单词字符。等价于 '[^A-Za-z0-9_]'。
        \xn  匹配 n，其中 n 为十六进制转义值。十六进制转义值必须为确定的两个数字长。例如，'\x41' 匹配 "A"。'\x041' 则等价于 '\x04' & "1"。正则表达式中可以使用 ASCII 编码。.
        \num  匹配 num，其中 num 是一个正整数。对所获取的匹配的引用。例如，'(.)\1' 匹配两个连续的相同字符。
        \n  标识一个八进制转义值或一个向后引用。如果 \n 之前至少 n 个获取的子表达式，则 n 为向后引用。否则，如果 n 为八进制数字 (0-7)，则 n 为一个八进制转义值。
        \nm  标识一个八进制转义值或一个向后引用。如果 \nm 之前至少有 nm 个获得子表达式，则 nm 为向后引用。如果 \nm 之前至少有 n 个获取，则 n 为一个后跟文字 m 的向后引用。如果前面的条件都不满足，若 n 和 m 均为八进制数字 (0-7)，则 \nm 将匹配八进制转义值 nm。
        \nml  如果 n 为八进制数字 (0-3)，且 m 和 l 均为八进制数字 (0-7)，则匹配八进制转义值 nml。
        \un  匹配 n，其中 n 是一个用四个十六进制数字表示的 Unicode 字符。例如， \u00A9 匹配版权符号 (?)。
    例子
        /\b([a-z]+)\b/i 单词数量
        /^(\w+):\/\/([^/:]+)(:\d*)?([^# ]*)$/  将一个URL解析为协议、域、端口及相对路径
        /^(?:Chapter|Section) [1-9][0-9]{0,1}$/ 定位章节的位置
        /[-a-z]/ A至z共26个字母再加一个-号。
        /ter\b/ 可匹配chapter，而不能terminal
        /\Bapt/ 可匹配chapter，而不能aptitude
        /Windows(?=95 |98 |NT )/ 可匹配Windows95或Windows98或WindowsNT,当找到一个匹配后，从Windows后面开始进行下一次的检索匹配。
        /^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$/ Email 合法格式检查
        ^[0-9]+$ 纯数据检查
        ^[0-9a-z]{1}[0-9a-z\-]{0,19}$ 用户名检查，字母和数字开始，只能含字母、数字、横杠

    模式修正符
        i 忽略大小写
        s 如果设定了此修正符，模式中的圆点元字符（.）匹配所有的字符，包括换行符
        e 只用在preg_replace(),在替换字符串中对逆向引用作正常的替换，将其作为 PHP 代码求值，并用其结果来替换所搜索的字符串。
        如:
        $p = '/\[colorFont\](.+?)\[\/colorFont\]/ie';
        $t = '"<img src='color.php?t=".urlencode("\1")."\'/>"';
        ecoh preg_replace($p,$t,$string);

        这里必须加上e修正,才能将匹配到的内容用urlencode处理
        U 贪婪模式,最近匹配
        如:/a[\w]+?e/U匹配abceadeddd中的abceade而不是abce,如果不加U修正,则匹配abce
        A 强制从字符串开头匹配,即自动在模式开头加上^
        m 当设定了此修正符，“行起始” ^ 和“行结束” $ 除了匹配整个字符串开头和结束外，还分别匹配其中的换行符的之后和之前。如果目标字符串中没有“\n”字符或者模式中没有 ^ 或 $，则设定此修正符没有任何效果。
        D 模式中的美元元字符仅匹配目标字符串的结尾。没有此选项时，如果最后一个字符是换行符的话，美元符号也会匹配此字符之前。如果设定了 m 修正符则忽略此选项
	例子
		匹配中文
			preg_match_all('/[^\x00-\x80]+/', '中华s人s民', $a)
			如果你的文件是gb2312的，用/[\xa0-\xff]{2}/
			如果是utf8的，用/[\xe0-\xef][\x80-\xbf]{2}/
		匹配邮箱地址
			preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', 'shao@gmail.com')
		替换空白字符
			$s = preg_replace('/[\s\v]+/','','	sss sdd ss ');
		替换
			$string = "April 15, 2003";
			$pattern = "/(\w+) (\d+), (\d+)/i";
			$replacement = "\${1}1,\${3}1-$2";
			echo preg_replace($pattern, $replacement, $string);
		匹配帐号是否合法(字母开头，允许5-6字节，允许字母数字下划线)
			preg_match('/^[a-zA-Z][a-zA-Z0-9_]{4,5}$/', 'a011a')
		匹配数字
			/^-\d*$/ 匹配负整数
			/^-?\d*$/ 匹配整数
		匹配浮点数
			preg_match("/^-?(\d*.\d*|0.\d*|0?.0+|0)$/", "11")
		匹配电话号码
			preg_match("/^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7}){1,1}(\-[0-9]{1,4}){0,1}$/","0511-22345678-11")
		匹配手机号码
			preg_match("/^1(3|5)\d{9}$/","13717897211")
相关函数：
		preg_match();对正则表达式进行一次匹配，匹配首次出现的，返回值0或1.
		preg_match_all();对正则表达式进行多次匹配，返回值匹配次数/False
        preg_grep();匹配数组内单元，只匹配一次。
		preg_replace();匹配并替换字符串中内容，默认全部匹配替换。
		preg_split();返回经正则表达式分割的数组。用法同split();



文件处理
	文件属性
		file_exists('1.php') 文件或目录是否存在
		filesize() 取得文件大小
		is_readable() 判断给定文件名是否可读
		is_writable() 判断给定文件名是否可写
		is_executable() 判断给定文件名是否可执行
	    filegroup($filename)取得该文件所属组的ID
		filectime() 获取文件的创造时间
		filemtime() 获取文件的修改时间
		fileatime() 获取文件的访问时间
		stat() 获取文件大部分属性值
	解析目录
		basename() 返回路径中的文件名部分
		dirname() 返回目录
		pathinfo() 返回目录名、基本名和扩展名的关联数组
	遍历目录
		opendir() 打开指定目录
		readdir() 返回目录中下一个文件的文件名
		closedir() 关闭指定目录
		rewinddir() 倒回目录句柄
			$dir_handle=opendir('.');
			while($file=readdir($dir_handle))
			{
				echo filesize($file).'___'.$file.'<br>';
			}
			closedir($dir_handle);
	建立和删除目录
		mkdir() 创建目录
		rmdir() 删除空目录
	文件操作
		fopen($filename,$mode)打开文件或者URL
		      r/r+  只读/读写方式打开，将文件指针指向文件头
		      w/w+  写入/读写方式打开，将文件指针指向文件头并将文件大小截为零。若文件不存在则尝试创建之
		fclose()关闭一个已打开的文件指针
		fwrite() 写入文件(可安全用于二进制文件)//fputs() fwrite的别名
		file_put_contents($文件名,$内容) 把内容存成文件
		file_get_contents() 从文件读出内容
	文件读取
		fread($handle,int $length)读取文件(可安全用于二进制文件)
		stream_get_contents()
		fgets()从文件指针中读取一行,并返回长度最多为length-1字节的字符串
		feof()测试文件指针是否到了文件结束的位置
		fgetc()从文件指针中读取字符,返回一个包含有一个字符的字符串
		file()把整个文件读入一个数组中,数组中的每个单元都是文件中相应的一行,包括换行符在内
		readfile() 读入一个文件并写入到输出缓冲
		ftell()返回文件指针的当前位置
		fseek() 移动文件指针到指定的位置SEEK_SET设定位置为当前位置;SEEK_CUR设定位置等于文件开头(默认); SEEK_END设定位置为文件尾。
		rewind() 移动文件指针到文件的开头
		flock() 文件锁定
		copy() 复制文件
		unlink() 删除文件
		ftruncate() 将文件截断到指定的长度
		rename() 重命名文件或目录
	文件控制
		chgrp($filename,$group)改变文件所属的组
		chmod($filename,$mode)尝试将filename所指定文件的模式改成mode所给定的
		chown($filename,$user)改变文件的所有者
	保存读取文件
		-----------把内容存成文件
		$cache_file = fopen('f:\1.txt', 'w+');
		fwrite($cache_file, $t);
		-----------把内容存成文件
		$s = "内容";
		file_put_contents('f:/2.txt',$s);
		-----------把文件内容读成字符串
		$s = file_get_contents('f:/2.txt');
		echo $s;
		-----------把文件内容按行读成字符串
		$handle = @fopen("f:/2.txt", "r");
		if ($handle)
		{
			while (!feof($handle))
			{
				$buffer = fgets($handle, 4096);
				echo $buffer.'<br>';
			}
			fclose($handle);
		}
		----------

session/cookie
	setcookie("MyCookie[foo]", 'Testing 1', time()+3600)
	session_start()
	ini_set('session.cookie_lifetime',0); session对应cookie存活时间
	ini_set('session.save_path', 'dir');
	ini_set('session.save_path', '2;session');session分两级存放
	ini_set('session.name','SNS');
	客户端禁用Cookie 则用URL传值
		session.use_trans_sid = 1 开启url传递sessionId php.ini
	session销毁


mysql
	$link = mysql_connect('localhost','root','password') or die(mysql_errno());连接到MYSQL服务器
	mysql_select_db('test') or die (mysql_errno());选择数据库
	mysql_query('SET NAMES gbk');设定从数据库中提取信息编码
	$sql = "SELECT * FROM test LIMIT 0,20";
	$result = mysql_query($sql) or die(mysql_errno());执行SQL语句
		while($msg = mysql_fetch_array($result)){
			print_r($msg);
		}
	mysql_db_query('dbname','$sql');发送一条sql语句(查询结束后不能自动切换回执行前的数据库，停留在当前库)
	mysql_free_result($result);释放结果内存
	mysql_close($link);关闭数据库

	mysql_insert_id();取得上一步 INSERT 操作产生的 ID

	mysql_fetch_array($result,MYSQL_ASSOC);从结果集中取得一行作为数组返回
		 MYSQL_ASSOC：关联数组；MYSQL_NUM：索引数组；MYSQL_BOTH：两者共用（默认值）
	mysql_fetch_object($result);从结果集中取得一行作为对象
	mysql_fetch_assoc($result);从结果集中取得一行作为关联数组
	mysql_fetch_row($result);从结果集中取得一行作为枚举数组
	mysql_num_fields($result);取得结果集中字段的数目
	mysql_num_rows($result);取得结果集中行的数目
	mysql_fetch_field($result);从结果集中取得列信息并作为对象返回
	  对象的属性为：
		name - 列名
		table - 该列所在的表名
		max_length - 该列最大长度
		not_null - 1，如果该列不能为 NULL
		primary_key - 1，如果该列是 primary key
		unique_key - 1，如果该列是 unique key
		multiple_key - 1，如果该列是 non-unique key
		numeric - 1，如果该列是 numeric
		blob - 1，如果该列是 BLOB
		type - 该列的类型
		unsigned - 1，如果该列是无符号数
		zerofill - 1，如果该列是 zero-filled

	mysql_result($result);取得结果数据,返回MYSQL结果集中一个单元的内容。不能和其它处理结果集的函数混合调用。
	mysql_data_seek($result , int $row_number);移动内部结果的指针

	mysql_errno([resource $link_identifier]);返回上一个 MySQL操作中的错误信息的数字编码
    mysql_error([resource $link_identifier]);返回上一个 MySQL操作产生的文本错误信息,没有错误则返回""。
	mysql_pconnect(['localhost','root','password',$client_flags ]);打开一个到 MySQL 服务器的持久连接.client_flags参数可以是以下常量的组合：MYSQL_CLIENT_COMPRESS，MYSQL_CLIENT_IGNORE_SPACE 或者 MYSQL_CLIENT_INTERACTIVE。
	mysql_drop_db();删除数据库，不建议使用


mysqli
	查询
		-------------------------------过程
		$db_host="localhost";   //连接的服务器地址
		$db_user="root";    //连接数据库的用户名
		$db_psw="root";     //连接数据库的密码
		$db_name="test"; //连接的数据库名称
		$mysqli=mysqli_connect($db_host,$db_user,$db_psw,$db_name);
		mysqli_query($mysqli,'SET NAMES utf8');
		$query="select * from users";
		$result=mysqli_query($mysqli,$query);
		while($row =mysqli_fetch_array($result)) //循环输出结果集中的记录
		{
			echo ($row['id'])."<br>";
			echo ($row['username'])."<br>";
			echo ($row['password'])."<br>";
			echo "<hr>";
		}
		mysqli_free_result($result);
		mysqli_close($mysqli);
		-------------------------------对象
		$db_host="localhost";   //连接的服务器地址
		$db_user="root";    //连接数据库的用户名
		$db_psw="root";     //连接数据库的密码
		$db_name="test"; //连接的数据库名称
		$mysqli=new mysqli($db_host,$db_user,$db_psw,$db_name);
		$mysqli->query('SET NAMES utf8');
		$query="select * from users";
		$result=$mysqli->query($query);
		if ($result)
		{
			if($result->num_rows>0) //判断结果集中行的数目是否大于0
			{
				while($row =$result->fetch_array()) //循环输出结果集中的记录
				{
					echo ($row[0])."<br>";
					echo ($row[1])."<br>";
					echo ($row[2])."<br>";
					echo "<hr>";
				}
			}
		}
		else
		{
			echo "查询失败";
		}
		$result->free();
		$mysqli->close();

	增、删、改
		$mysqli=new mysqli("localhost","root","root","sunyang");//实例化mysqli
		$query="delete from employee where emp_id=2";
		$result=$mysqli->query($query);
		if ($result){
			echo "删除操作执行成功";
		}else{
			echo "删除操作执行失败";
		}
		$mysqli->close();

	绑定结果
		$mysqli=new mysqli("localhost","root","root","test");//实例化mysqli
		$query="select * from users";
		$result=$mysqli->prepare($query); //进行预准备语句查询
		$result->execute();               //执行预准备语句
		$result->bind_result($id,$username,$password);  //绑定结果
		while ($result->fetch()) {
			echo $id.'_';
			echo $username.'_';
			echo $password;
			echo "<br>";
		}
		$result->close();                             //关闭预准备语句
		$mysqli->close();                             //关闭连接

	绑定参数
		$mysqli=new mysqli("localhost","root","root","test");//实例化mysqli
		$query="insert into users (id, username, password)   values ('',?,?)";
		$result=$mysqli->prepare($query);
		$result->bind_param("ss",$username,$password);   //绑定参数 I:integer D:double S:string B:blob
		$username='sy0807';
		$password='employee7';
		$result->execute();                               //执行预准备语句
		$result->close();
		$mysqli->close();

	绑定参数、绑定结果
		$mysqli=new mysqli("localhost","root","root","test");      //实例化mysqli
		$query="select * from users where id < ?";
		$result=$mysqli->prepare($query);
		$result->bind_param("i",$id);                 //绑定参数
		$id=10;
		$result->execute();
		$result->bind_result($id,$username,$password);         //绑定结果
		while ($result->fetch()) {
			echo $id."_";
			echo $username."_";
			echo $password;
			echo "<br>";
		}
		$result->close();
		$mysqli->close();

	多条查询语句
		$mysqli=new mysqli("localhost","root","root","test");//实例化mysqli
		$query = "select id from users ;";
		$query .= "select id from test ";
		if ($mysqli->multi_query($query)) {                 //执行多个查询
			do {
				if ($result = $mysqli->store_result()) {
					while ($row = $result->fetch_row()) {
						echo $row[0];
						echo "<br>";
					}
					$result->close();
				}
				if ($mysqli->more_results()) {
					echo ("-----------------<br>");                       //连个查询之间的分割线
				}
			} while ($mysqli->next_result());
		}
		$mysqli->close();//关闭连接

pdo
	查询
		$db = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
		$sql="SELECT * FROM users";
		$result = $db->query($sql);
		foreach ($result as $row)
		{
			var_dump($row);
		}
		$db = null;
	增、删、改、事务开启
		try
		{
			$db = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
			$db->beginTransaction();
			$a = $db->exec("insert into users (id, username, password) values ('', 'Joe', 'Bloggs')");
			if($a == false)
			{
				throw new Exception("sql1执行失败");
			}
			$b = $db->exec("insert into users (id, username, password,kkk) values ('', 'Joe', 'Bloggs')");
			if($b == false)
			{
				throw new Exception("sql2执行失败");
			}
			$db->commit();
			$db = null;
		}
		catch (Exception $ex)
		{
			echo $ex;
			$db->rollback();
		}

缓存
	Memcache
		.下载memcached， http://www.danga.com/memcached/ ; 2.解压，比如放在 D:\memcached-1.2.1 ; 3.DOS下输入‘D:\memcached-1.2.1\memcached.exe -d install’,进行安装(注意‘’不要输入); 4.再次输入‘D:\memcached-1.2.1\memcached.exe -d start’启动memcached。   注意：memcached以后会随机启动。这样memcached就已经安装完毕了。

		$memcache = new Memcache;
		$memcache->addServer('172.19.5.199',11211);
		$memcache->addServer('172.19.5.13',11211);
		//$memcache->connect('localhost', 11211) or die ("Could not connect");
		//$version = $memcache->getVersion();
		//echo "Server's version: ".$version;
		$memcache->set('key3',array(1,2,3));
		var_dump($memcache->get('key3'));

        var_dump(class_exists('memcache'));//判断memcache配置是否成功

     Memcache常用函数
    ！  Memcache::add — 添加一个值，如果已经存在，则返回false
        Memcache::addServer — 添加一个可供使用的服务器地址
    ！  Memcache::close — 关闭一个Memcache对象
    ！  Memcache::connect — 创建一个Memcache对象
        memcache_debug — 控制调试功能
        Memcache::delete — 删除一个key值
    ！  Memcache::flush — 清除所有缓存的数据
        Memcache::get — 获取一个key值
        Memcache::getExtendedStats — 获取进程池中所有进程的运行系统统计
        Memcache::getServerStatus — 获取运行服务器的参数
    ！  Memcache::getStats — 返回服务器的一些运行统计信息
    ！  Memcache::getVersion — 返回运行的Memcache的版本信息
    ！  Memcache::increment — 对保存的某个key中的值进行加法操作
    ！  Memcache::decrement — 对保存的某个key中的值进行减法操作
        Memcache::pconnect — 创建一个Memcache的持久连接对象
        Memcache::replace — R对一个已有的key进行覆写操作
        Memcache::set — 添加一个值，如果已经存在，则覆写
        Memcache::setCompressThreshold — 对大于某一大小的数据进行压缩
        Memcache::setServerParams — 在运行时修改服务器的参数

	ob

        ob_start();打开输出缓冲区，开启后ob命令才有意义
        ob_clean();删除内部缓冲区的内容
        ob_end_clean();删除内部缓冲区的内容，并且关闭内部缓冲区
        flush();刷新缓冲区
        ob_flush();刷新缓冲区，输出缓冲区内容
        ob_end_flush();刷新缓冲区，输出缓冲区内容，关闭缓冲区
        ob_get_contents();获取缓冲区内容
        ob_get_length();获取缓冲区内容长度

		ob_start()
		$content = ob_get_contents();
		ob_clean();
		$cache_file = fopen('f:\1.html', 'w+');
		fwrite($cache_file, $content);

		页面静态化--------------------------------------
		ob_start();
		$static_file = '1.html';//静态页面
		$php_file = basename(__FILE__);//当前动态页面
		if (!file_exists($static_file) ||
			((filemtime($static_file)+10) < time()) || //缓存固定时间
			filemtime($php_file) > filemtime($static_file)) //源文件已修改
		{
			echo '静态页面示例';
			echo 'erer';
			$c = ob_get_contents();
			ob_clean();
			file_put_contents($static_file, $c);
		}
		$s = file_get_contents($static_file);
		echo $s;
		-------------------------------------------------
		ob_implicit_flush($p)  $p:0:关闭 1:开启(每次输出后都自动刷新，而不再需要去调用flush())
		ob_list_handlers 列出所有使用的输出句柄
		output_add_rewrite_var
			output_add_rewrite_var('var', 'value');
			echo '<a href="file.php">link</a>';
			输出：<a href="file.php?var=value">link</a>
		output_reset_rewrite_vars
			output_add_rewrite_var('var', 'value');
			echo '<a href="file.php">link</a>';//输出：<a href="file.php?var=value">link</a>
			ob_flush();
			output_reset_rewrite_vars();
			echo '<a href="file.php">link</a>';//输出：<a href="file.php">link</a>

伪静态
	首先：
	必须要空间支持 Rewrite 以及对站点目录中有 .htaccess 的文件解析,才有效.
	如何让空间支持Rewrite 和 .htaccess 的文件解析呢 往下看
	第一步:要找到apache安装目录下的httpd.cof文件,在里面找到
	<Directory />
		Options FollowSymLinks
		AllowOverride none
	</Directory>
	把none改all,
	第二步：找到以下内容：
	#LoadModule rewrite_module modules/mod_rewrite.so
	改为
	LoadModule rewrite_module modules/mod_rewrite.so
	第三步：保存重启apache。
	ok。
	其次是.htaccess的书写规则：

		<IfModule mod_rewrite.c>
			RewriteEngine On
			RewriteBase /
			#打开允许符号链接
			Options FollowSymLinks
			RewriteRule smarty/([0-9]+)/([0-9]+) smarty/index.php?id=$1&name=$2
		</IfModule>

	.htaccess加入以下内容
	RewriteEngine On
	RewriteBase /
	RewriteRule ^(.*)list-id([0-9]+)\.html$ $1/company/search.php?sectorid2=$2
	RewriteRule ^(.*)cominfo-([a-z0-9]+)\.html$ $1/member/index.php?uid=$2&type=cominfo
	RewriteRule ^(.*)list-([0-9]+)-([0-9]+)\.html$ $1/plus/list.php?typeid=$2&PageNo=$3
	RewriteCond %{HTTP_HOST} ^[a-z0-9\-]+\.lujin\.com$
	RewriteCond %{HTTP_HOST} !^(www|bbs)\.lujin\.com$
	RewriteRule ^/?$ /%{HTTP_HOST}
	RewriteRule ^/([a-z0-9\-]+)\.lujin\.com/?$ /member/index.php?uid=$1 [L]
	对上面的一些解释
	RewriteRule ^(.*)list-id([0-9]+)\.html$ $1/company/search.php?sectorid2=$2
	这条是把企业库的分类进行伪静态处理
	原先假设访问地址为http://www.xxx.com/company/search.php?sectorid2=1
	现在地址为http://www.xxx.com/list-id1.html

	优点：1、伪静态处理加速搜索引擎收入
	2、地址映射到根目录，增加权重，提高排名

序列化：暂时封存一些对象方法(以文件形式)，修要用时再解封
	__sleep()
	__wakeup()
	-----------------
	$a = array("1"=>"a","2"=>"b","3"=>"c","4"=>"d");
	$b = serialize($a);/*序列化*/
	var_dump($b);
	$f = unserialize($b);/*解析*/
	var_dump($f);
	---------------------
	class S
	{
		public $t = 111;
		public function t()
		{
			echo 't function';
		}
	}
	$s = new S;
	$t = serialize($s);
	$e = unserialize($t);
	echo $e->t();
	echo $e->t;
	--------------------
	class S
	{
		public $id;
		public $name;
		public function f()
		{
			echo 'f function';
		}
		function __sleep()
		{
			$this->id = uniqid();
			return array('id','name');
		}
		function __wakeup()
		{
			//$this->id = uniqid();
		}
	}
	$s = new S();
	$s->name = 'name';
	$e = serialize($s);
	$t = unserialize($e);
	echo $t->id.'_',$t->name,' ';
	echo $t->f();
	----------------------------
	class S
	{
		public $t = 111;
		public function t()
		{
			echo 't function';
		}
	}
	$s = new S;
	$t = serialize($s);
	$cache_file = fopen('f:/1.txt', 'w+');
	fwrite($cache_file, $t);
	/*
	die;
	$e = unserialize($t);
	echo $e->t();
	echo $e->t;
	*/
	$handle = @fopen("f:/1.txt", "r");
	if ($handle)
	{
		while (!feof($handle))
		{
			$buffer = fgets($handle, 4096);
			break;
		}
		fclose($handle);
	}
	$e = unserialize($buffer);
	echo $e->t();
	echo $e->t;
	-----------------------------------------

ThinkPHP2.0
	入口文件配置
		define('STRIP_RUNTIME_SPACE', false);生成的~runtime.php文件是否去空白和注释
		define('NO_CACHE_RUNTIME', true);不生成核心缓存文件

	查询
		按照id排序显示前6条记录
		$Form	= M("Form");
		$list	=	$Form->order('id desc')->limit(6)->select();

	取得模板显示变量的值
		$this->assign('tt', 'vvvvvvvvvvvv');
		echo $this->get('tt')

	成功失败提示页
		if(false !==$Form->add()) {
			$this->success('数据添加成功！');
		}else{
			$this->error('数据写入错误');
		}

	自动验证
		array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		验证规则：require 字段必须、email 邮箱、url URL地址、currency 货币、number 数字
		Model:: MODEL_INSERT 或者1新增数据时候验证
		Model:: MODEL_UPDATE 或者2编辑数据时候验证
		Model:: MODEL_BOTH 或者3 全部情况下验证（默认）

		protected $_validate = array(
			array('verify','require','验证码必须！'), //默认情况下用正则进行验证
			array('name','','帐号名称已经存在！',0,’unique’,1), // 在新增的时候验证name字段是否唯一
			array('value',array(1,2,3),'值的范围不正确！',2,’in’), // 当值不为空的时候判断是否在一个范围内
			array('repassword','password','确认密码不正确',0,’confirm’), // 验证确认密码是否和密码一致
			array('password','checkPwd','密码格式不正确',0,’function’), // 自定义函数验证密码格式
		);

apache多域名配置
	NameVirtualHost *:80
	Alias /php/  "f:/php/"
	<Directory "f:/php/">
		Options Indexes
		Order allow,deny
		Allow from all
	</Directory>
	<VirtualHost *:80>
		DocumentRoot F:/php
		ServerPath F:/php
		ServerAlias www.a.com
		ServerName www.a.com
	</VirtualHost>


	<Directory "F:/php2">
		Options Indexes
		Order allow,deny
		Allow from all
	</Directory>
	<VirtualHost *:80>
		ServerName www.b.com
		ServerAlias www.b.com
		ServerPath F:/php2
		DocumentRoot F:/php2
	</VirtualHost>










