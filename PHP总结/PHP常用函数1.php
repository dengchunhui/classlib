<?php 
	//隐藏手机号 ***
	function hideMobile($mobile){
		 if($mobile!="")
			return preg_replace('#(\d{3})\d{5}(\d{3})#', '${1}*****${2}',$mobile);
		 else
			return "";
	}
	
	//隐藏身份证号
	function hideIdCard($idcard){
		 if($idcard!="")
			return preg_replace('#(\d{14})\d{4}|(\w+)#', '${1}****',$idcard);
		 else
			return "";

	}
	
	//隐藏邮箱
	function hideEmail($email){
		if($email!="")
			return preg_replace('#(\w{2})\w+\@+#', '${1}****@${3}', $email);
		else
			return "";
	}
	
	/**
	 * 计算两个日期差几个月
	 */
	function how_much_month($start_time,$end_time){
		if($start_time=="" || $end_time=="")
		{
			return "";
		}
		$time1 = to_date($start_time,"Y")*12 + to_date($start_time,"m");
		$time2 = to_date($end_time,"Y")*12 + to_date($end_time,"m");
		return $time2 - $time1;
	}
	
	//获取时间
	function get_mktime($mktime){
		if ($mktime=="") return "";
		$dtime = trim(ereg_replace("[ ]{1,}"," ",$mktime));
		$ds = explode(" ",$dtime);
		$ymd = explode("-",$ds[0]);
		if (isset($ds[1]) && $ds[1]!=""){
			$hms = explode(":",$ds[1]);
			$mt = mktime(empty($hms[0])?0:$hms[0],!isset($hms[1])?0:$hms[1],!isset($hms[2])?0:$hms[2],!isset($ymd[1])?0:$ymd[1],!isset($ymd[2])?0:$ymd[2],!isset($ymd[0])?0:$ymd[0]);
		}else{
			$mt = mktime(0,0,0,!isset($ymd[1])?0:$ymd[1],!isset($ymd[2])?0:$ymd[2],!isset($ymd[0])?0:$ymd[0]);
		}
		return $mt;
	}
	
	//获取GMTime
	function get_gmtime()
	{
		return (time() - date('Z'));
	}
	
	function to_date($utc_time, $format = 'Y-m-d H:i:s') {
		if (empty ( $utc_time )) {
			return '';
		}
		$timezone = intval(app_conf('TIME_ZONE'));
		$time = $utc_time + $timezone * 3600;
		return date ($format, $time );
	}
	
	/**
	 * 友好时间
	 */
	function fdate($time) {
		if (!$time)
			return false;
		$fdate = '';
		$d = time() - intval($time);
		$ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //年
		$md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //月
		$byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
		$yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
		$dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
		$td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
		$atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
		if ($d == 0) {
			$fdate = '刚刚';
		} else {
			switch ($d) {
				case $d < $atd:
					$fdate = date('Y年m月d日', $time);
					break;
				case $d < $td:
					$fdate = '后天' . date('H:i', $time);
					break;
				case $d < 0:
					$fdate = '明天' . date('H:i', $time);
					break;
				case $d < 60:
					$fdate = $d . '秒前';
					break;
				case $d < 3600:
					$fdate = floor($d / 60) . '分钟前';
					break;
				case $d < $dd:
					$fdate = floor($d / 3600) . '小时前';
					break;
				case $d < $yd:
					$fdate = '昨天' . date('H:i', $time);
					break;
				case $d < $byd:
					$fdate = '前天' . date('H:i', $time);
					break;
				case $d < $md:
					$fdate = date('m月d H:i', $time);
					break;
				case $d < $ld:
					$fdate = date('m月d', $time);
					break;
				default:
					$fdate = date('Y年m月d日', $time);
					break;
			}
		}
		return $fdate;
	}
	
	/**
	 * 获取指定时间与当前时间的时间间隔
	 *
	 * @access  public
	 * @param   integer      $time
	 *
	 * @return  string
	 */
	function getBeforeTimelag($time)
	{
		if($time == 0)
			return "";

		static $today_time = NULL,
				$before_lang = NULL,
				$beforeday_lang = NULL,
				$today_lang = NULL,
				$yesterday_lang = NULL,
				$hours_lang = NULL,
				$minutes_lang = NULL,
				$months_lang = NULL,
				$date_lang = NULL,
				$sdate = 86400;

		if($today_time === NULL)
		{
			$today_time = TIME_UTC;
			$before_lang = '前';
			$beforeday_lang = '前天';
			$today_lang = '今天';
			$yesterday_lang = '昨天';
			$hours_lang = '小时';
			$minutes_lang = '分钟';
			$months_lang =  '月';
			$date_lang =  '日';
		}

		$now_day = to_timespan(to_date($today_time,"Y-m-d")); //今天零点时间
		$pub_day = to_timespan(to_date($time,"Y-m-d")); //发布期零点时间

		$timelag = $now_day - $pub_day;

		$year_time = to_date($time,'Y');
		$today_year = to_date($today_time,'Y');

		if($year_time < $today_year)
			return to_date($time,'Y:m:d H:i');

		$timelag_str = to_date($time,' H:i');

		$day_time = 0;
		if($timelag / $sdate >= 1)
		{
			$day_time = floor($timelag / $sdate);
			$timelag = $timelag % $sdate;
		}

		switch($day_time)
		{
			case '0':
				$timelag_str = $today_lang.$timelag_str;
			break;

			case '1':
				$timelag_str = $yesterday_lang.$timelag_str;
			break;

			case '2':
				$timelag_str = $beforeday_lang.$timelag_str;
			break;

			default:
				$timelag_str = to_date($time,'m'.$months_lang.'d'.$date_lang.' H:i');
			break;
		}
		return $timelag_str;
	}

	/**
	 * 对象转换成数组
	 */
	function object_to_array($obj) {
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		foreach ($_arr as $key => $val) {
			$val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
			$arr[$key] = $val;
		}
		return $arr;
	}

	//获取客户端IP
	function get_client_ip() {
		//使用wap时，是通过中转方式，所以要在wap/index.php获取客户ip,转入到:sjmapi上 chenfq by add 2014-12-17
		if (isset($GLOBALS['request']['client_ip']) && !empty($GLOBALS['request']['client_ip']))
			$ip = $GLOBALS['request']['client_ip'];
		else if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
			$ip = getenv ( "HTTP_CLIENT_IP" );
		else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
			$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
		else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
			$ip = getenv ( "REMOTE_ADDR" );
		else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
			$ip = $_SERVER ['REMOTE_ADDR'];
		else
			$ip = "unknown";
		return strim($ip);
	}
	
	/**
	 * 判断函数是否存在，不存在则返回false
	 */
	function IsExiest($val){
		if (isset($val) && ($val!="" || $val==0)){
			return $val;
		}else{
			return false;
		}
	}
	
	/**
	 * 判断月份
	 */
	function get_times($data=array()){  
		//获取时间戳
		if (isset($data['time']) && $data['time']!=""){
			$time = $data['time'];//时间
		}elseif (isset($data['date']) && $data['date']!=""){
			$time = strtotime($data['date']);//日期
		}else{
			$time = time();//现在时间
		}

		//获取转换类型
		if (isset($data['type']) && $data['type']!=""){ 
			$type = $data['type'];//时间转换类型，有day week month year
		}else{
			$type = "month";
		}

		//设置默认几个月
		if (isset($data['num']) && $data['num']!=""){ 
			$num = $data['num'];
		}else{
			$num = 1;
		}

		if ($type=="month"){
			$month = date("m",$time);
			$year = date("Y",$time);
			//判断今天是几号
			$td = date('d',$time);
			//判断下个月是否是明年了
			if(intval($month+$num)>12){
				$month = $month-12;
				$year = $year+1;
			}
			$days = cal_days_in_month(CAL_GREGORIAN, $month+$num, $year);
			//判断下个月是否有今天
			if($days<$td){ //无 则返回下个月的最后一天	
				$_result = strtotime($year.'-'.($month+$num).'-'.$days);
			}else{
				$_result = strtotime("$num month",$time);
			}	
		}else{
			$_result = strtotime("$num $type",$time);
		}
		if (isset($data['format']) && $data['format']!=""){ 
			return date($data['format'],$_result);
		}else{
			return $_result;
		}

	}
	
	/*
	 * 生成单号
	* @param  string	$mod	model名
	* @return string	$field	订单字段名
	* @access string	$type	订单首字母：B-借款；C-提现；R-充值；T-投资 ； Z-债权转让
	* @version by li 2015-01-9
	* @return	string	$order	订单号
	*/
	function payOrder($mod,$field,$type='B'){
		$type = strtoupper($type);
		$order = $type . date('YmdHis') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 11, 13), 1))), 0, 3);
		$Model = D($mod);
		$data[$field] = $order;
		$v = $Model->where($data)->find();
		if ($v) {
			$order = $this->payCard($mod,$field,$type);
		}
		return $order;
	}
	
	//过滤注入
	function filter_injection(&$request)
	{
		$pattern = "/(select[\s])|(insert[\s])|(update[\s])|(and[\s])|(sleep[\w\W])|(delete[\s])|(from[\s])|(where[\s])/i";
		foreach($request as $k=>$v)
		{
					if(preg_match($pattern,$k,$match))
					{
							die("SQL Injection denied!");
					}
					if(is_array($v))
					{
						filter_injection($v);
					}
					else
					{

						if(preg_match($pattern,$v,$match))
						{
							die("SQL Injection denied!");
						}
					}
		}

	}
	
	//判断是不是HTTPS协议
	if ((isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https')) {
		define('FUYOU_CALLBACK_URL','https://jxch.5pa.cn/');
	}else{
		define('FUYOU_CALLBACK_URL','http://jxch.5pa.cn/');
	}

	//PHP-CLI模式访问控制
	$type = strtolower(php_sapi_name());
	if(!$type || (isset($type) && $type != 'cli'))
	{
		header('Content-Type:text/html;charset=utf-8');
		die('对不起，您没有访问权限！');
	}

	function get_http()
	{
		return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
	}

	function get_domain()
	{
		/* 协议 */
		$protocol = get_http();

		/* 域名或IP地址 */
		if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
		{
			$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
		}
		elseif (isset($_SERVER['HTTP_HOST']))
		{
			$host = $_SERVER['HTTP_HOST'];
		}
		else
		{
			/* 端口 */
			if (isset($_SERVER['SERVER_PORT']))
			{
				$port = ':' . $_SERVER['SERVER_PORT'];

				if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
				{
					$port = '';
				}
			}
			else
			{
				$port = '';
			}

			if (isset($_SERVER['SERVER_NAME']))
			{
				$host = $_SERVER['SERVER_NAME'] . $port;
			}
			elseif (isset($_SERVER['SERVER_ADDR']))
			{
				$host = $_SERVER['SERVER_ADDR'] . $port;
			}
		}

		return $protocol . $host;
	}

	function get_host()
	{
		/* 域名或IP地址 */
		if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
		{
			$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
		}
		elseif (isset($_SERVER['HTTP_HOST']))
		{
			$host = $_SERVER['HTTP_HOST'];
		}
		else
		{
			if (isset($_SERVER['SERVER_NAME']))
			{
				$host = $_SERVER['SERVER_NAME'];
			}
			elseif (isset($_SERVER['SERVER_ADDR']))
			{
				$host = $_SERVER['SERVER_ADDR'];
			}
		}
		return $host;
	}
	
	//过滤请求
	function filter_request(&$request)
	{
			if(MAGIC_QUOTES_GPC)
			{
				foreach($request as $k=>$v)
				{
					if(is_array($v))
					{
						filter_request($request[$k]);
					}
					else
					{
						$request[$k] = stripslashes(trim($v));
					}
				}
			}

	}
	
	//发送URL请求
	function open_url($URL, $ip = "", $cks = "", $cksfile = "", $post = "", $ref = "", $fl = 0, $nbd = 0, $hder = 0, $tmout = "120")
	{//,$ctimeout="60
		//echo $URL . "\r\n<BR>";
		if ($cks && $cksfile) {
			$logstr = "[[cookie]]: There is a NULL bettwn cks and cksfile at one time! \r\n";
			echo $logstr;
			return 0;
		}
		$ch = curl_init(); //初始化一个curl资源(resource)
		curl_setopt($ch, CURLOPT_URL, $URL); //初始化一个url
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $tmout); //设置连接时间
		curl_setopt($ch, CURLOPT_TIMEOUT, $tmout); //设置执行时间
		if ($ip) { //设置代理服务器
			curl_setopt($ch, CURLOPT_PROXY, $ip);
		}
		if ($cksfile) { //设置保存cookie 的文件路径
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cksfile); //读上次cookie
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cksfile); //写本次cookie
		}
		if ($cks) { //设置cookies字符串，不要与cookie文件同时设置
			curl_setopt($ch, CURLOPT_COOKIE, $cks);
		}
		if ($ref) { //url reference
			curl_setopt($ch, CURLOPT_REFERER, $ref);
		}

		if ($post) { //设置post 字符串
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $fl); //设置是否允许页面跳转 1 跳转，0 不跳转
		curl_setopt($ch, CURLOPT_HEADER, $hder); //设置是否返回头文件 1返回，0 不返回
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //
		curl_setopt($ch, CURLOPT_NOBODY, $nbd); //设置是否返回body信息，1 不返回，0 返回
		//设置用户浏览器信息
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)');

		//执行
		$re = curl_exec($ch); //
		if ($re === false) { //检错
			$logstr = "[[curl]]: " . curl_error($ch);
			echo $logstr;
		}
		curl_close($ch); //关闭curl资源
		return $re; //返回得到的结果
	}
	
	function adddeepslashes(&$request)
	{

				foreach($request as $k=>$v)
				{
					if(is_array($v))
					{
						adddeepslashes($request[$k]);
					}
					else
					{
						$request[$k] = addslashes(trim($v));
					}
				}
	}
	
	function stripslashes_deep($value) {
		if (is_array($value)) {
			$value = array_map('stripslashes_deep', $value);
		} elseif (is_object($value)) {
			$vars = get_object_vars($value);
			foreach ($vars as $key => $data) {
				$value->{$key} = stripslashes_deep($data);
			}
		} else {
			$value = stripslashes($value);
		}

		return $value;
	}
	
	/**
	 * 删除指定目录（文件夹）中的所有文件,不包括此文件夹
	 * @param $param array('session_id' => '缓存ID')
	 * @return Null
	 */
	function DelFile($dir) { 
		if (is_dir($dir)) { 
			$dh=opendir($dir);//打开目录 //列出目录中的所有文件并去掉 . 和 .. 
			while (false !== ( $file = readdir ($dh))) { 
				if($file!="." && $file!="..") {
					$fullpath=$dir."/".$file; 
					if(!is_dir($fullpath)) { 
						unlink($fullpath);
					} else { 
						del_file($fullpath); 
					} 
				}
			}
			closedir($dh); 
		} 
	}
	
	/**
	 * 9,获取目录的名称
	 * @param $param array('dir' => '地址')
	 * @return Null
	 */
	function get_file($dir,$type='dir'){
		$result = "";
		if (is_dir($dir)) {
			if ($dh = opendir($dir)){
				while (($file = readdir($dh)) !== false){
					$_file = $dir."/".$file;
					if ($file !="." && $file != ".." && filetype($_file)==$type ){
						$result[] = $file;
					}
				}
				closedir($dh);
			}
		}
		return $result;
	}
	
	function read_file($filename) {
		if ( file_exists($filename) && is_readable($filename) && ($fd = @fopen($filename, 'rb')) ) {
			$contents = '';
			while (!feof($fd)) {
				$contents .= fread($fd, 8192);
			}
			fclose($fd);
			return $contents;
		} else {
			return false;
		}
	}	
	
	//清除目录或文件
	function clear_dir_file($path)
	{
	   if ( $dir = opendir( $path ) )
	   {
				while ( $file = readdir( $dir ) )
				{
					$check = is_dir( $path. $file );
					if ( !$check )
					{
						@unlink( $path . $file );
					}
					else
					{
						if($file!='.'&&$file!='..')
						{
							clear_dir_file($path.$file."/");
						}
					 }
				}
				closedir( $dir );
				rmdir($path);
				return true;
	   }
	}
	
	/**
	 * 产生随机数
	 * @param $length 产生随机数长度
	 * @param $type 返回字符串类型
	 * @param $hash 是否由前缀，默认为空. 如:$hash = 'zz-' 结果zz-823klis
	 * @return 随机字符串 $type = 0：数字+字母
	 *         $type = 1：数字
	 *         $type = 2：字符
	 */
	function random($length, $type = 0, $hash = '') {
		if ($type == 0) {
			$chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		} else if ($type == 1) {
			$chars = '0123456789';
		} else if ($type == 2) {
			$chars = 'abcdefghijklmnopqrstuvwxyz';
		}
		$max = strlen ( $chars ) - 1;
		mt_srand ( ( double ) microtime () * 1000000 );
		for($i = 0; $i < $length; $i ++) {
			$hash .= $chars [mt_rand ( 0, $max )];
		}
		return $hash;
	}
	
/**导出excel格式表
@author: chenwei
@param: $filename要保存的excel文件名称
@param: $title要保存的excel文件标题
@param: $data 要保存的数据(一个二维数组)
*/
function exportData($filename,$title,$data){
	header('Content-Type: application/octet-stream');
	if(preg_match("/MSIE/", $_SERVER["HTTP_USER_AGENT"])){
	$filename = str_replace("+", "%20", urlencode($filename));
	}
	header("Content-type: application/vnd.ms-excel;");
	header("Content-disposition: attachment; filename="  . $filename . ".xls");
	$out_put ='';
	$out_put .=<<<EOT
	<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		<style id="Classeur1_16681_Styles"></style>
	</head>
	<body>
		<div id="Classeur1_16681" align=center x:publishsource="Excel">
			<table border=1 align=center style="margin:0 auto" cellpadding=0 cellspacing=0 width=100%>
EOT;
   if (is_array($title)){
		$v_title .= '<tr align=center>';
		foreach ($title as $key => $value){
			$v_title .= '<td align=center class=xl2216681 nowrap style="height:40px;background-color:yellow;font-weight:bold;font-size:16px;">'.$value.'</td>';
		}
		$v_title .= '</tr>';
		$out_put .= $v_title;
	}
	if (is_array($data)){
		foreach ($data as $key => $value){
		$v_data .= '<tr align=center>';
			foreach ($value as $_key => $_value){

				if(is_int($_value)) $_value = intval($_value);
				$v_data .= "<td align=center style='vnd.ms-excel.numberformat:@;height:25px;' class=xl2216681 nowrap>".$_value.'</td>';
			}
		$v_data .= '</tr>';
		}
		$out_put .= $v_data;
	}
   $out_put .=<<<EOF
   </table>
		</div>
	</body>
</html>
EOF;
	echo $out_put;
}

	/*处理金额小数点位数 参数可为数值或数组*/
	function decimal_num($data){
		if($data==0||$data==''||$data==null){
			return 0.00;
		}
		global $_DY;
		$_money_point = empty($_DY['money_point'])?2:$_DY['money_point'];
		if(is_array($data)){
			foreach($data as $k=>$v){
				$data[$k] = decimal_num($v);
			}
		}else{
			if(strpos($data, '.')>0){
			$data = substr_replace($data, '', strpos($data, '.') + $_money_point+1);
			}
		}
		return $data;
	}
	
	/*处理金额 ，格式输出*/
	function doFormatMoney($money){
		//保留两位小数 四舍五入
		$money=round($money,2);
		$_money = explode('.',$money);
		if(empty($_money[1])){
			$_money[1]="00";
		}
		$money = $_money[0];
		$tmp_money = strrev($money);
		$format_money = ""; 
		for($i = 3;$i<strlen($money);$i+=3){
			$format_money .= substr($tmp_money,0,3).",";
			 $tmp_money = substr($tmp_money,3);
		 }
		$format_money .=$tmp_money;
		$format_money = strrev($format_money);
		
		return $format_money.'.'.$_money[1];;
	}
	
	//金额转换格式化
	function format_price($price,$decimals=2)
	{
		return app_conf("CURRENCY_UNIT")."".number_format($price,2);
	}
	
	/*
	 * 生成单号
	* @param  string	$mod	model名
	* @return string	$field	订单字段名
	* @access string	$type	订单首字母：B-借款；C-提现；R-充值；T-投资 ； Z-债权转让
	* @version by li 2015-01-9
	* @return	string	$order	订单号
	*/
	function payOrder($mod,$field,$type='B'){
		$type = strtoupper($type);
		$order = $type . date('YmdHis') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 11, 13), 1))), 0, 3);
		$Model = D($mod);
		$data[$field] = $order;
		$v = $Model->where($data)->find();
		if ($v) {
			$order = $this->payCard($mod,$field,$type);
		}
		return $order;
	}
	
	//utf8 字符串截取
	function msubstr($str, $start=0, $length=15, $charset="utf-8", $suffix=true)
	{
		if(function_exists("mb_substr"))
		{
			$slice =  mb_substr($str, $start, $length, $charset);
			if($suffix&$slice!=$str) return $slice."…";
			return $slice;
		}
		elseif(function_exists('iconv_substr')) {
			return iconv_substr($str,$start,$length,$charset);
		}
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
		if($suffix&&$slice!=$str) return $slice."…";
		return $slice;
	}
	
	/**
	*截取替换
	*/
	function utf_substr($str){
		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
		preg_match_all($pa, $str, $t_string);
		if(count($t_string[0])==1)
			return $str;
		if(count($t_string[0])==2)
			return cut_str($str, 1, 0).'***';
		else
			return cut_str($str, 1, 0).'***'.cut_str($str, 1, -1);
	}
	
	/**
	  * PHP获取字符串中英文混合长度
	  * @param $str string 字符串
	  * @param $$charset string 编码
	  * @return 返回长度，1中文=1位，2英文=1位
	  */
	  function strLength($str,$charset='utf-8'){
		if($charset=='utf-8') $str = iconv('utf-8','gb2312',$str);
		$num = strlen($str);
		$cnNum = 0;
		for($i=0;$i<$num;$i++){
			if(ord(substr($str,$i+1,1))>127){
				$cnNum++;
				$i++;
			}
		}
		$enNum = $num-($cnNum*2);
		$number = ($enNum/2)+$cnNum;
		return ceil($number);
	 }

	//字符编码转换
	if(!function_exists("iconv"))
	{
		function iconv($in_charset,$out_charset,$str)
		{
			require 'libs/iconv.php';
			$chinese = new Chinese();
			return $chinese->Convert($in_charset,$out_charset,$str);
		}
	}

	//JSON兼容
	if(!function_exists("json_encode"))
	{
		function json_encode($data)
		{
			require_once 'libs/json.php';
			$JSON = new JSON();
			return $JSON->encode($data);
		}
	}
	
	/**
	 * 生成用户密码密文
	 */
	public function password_encrypt($password,$cryptograph=null) {
		$str="";
		if($cryptograph==null){

			for($i=1;$i<=5;$i++){
				$str.=substr("abcdefghijklmnopqrstuvwxyz1234567890",rand(0,35),1);
			}
		}
		else{
			$str=substr($cryptograph,0,5);
		}		
		$str.= md5($str.$password."YIOISDIFLLSLJIOU987998902-LLK>JJKLKLJSDF=LK23LKKL2349@&KJLJKSLKLKLJSFDKLJSKLJF<DKLJSFDKJKJLSD|FKJ.LJ");
		$str = substr($str,0,32);
		return $str;
	}
	
	/**
	 * 加密程序
	 */
	function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
		// 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
		$ckey_length = 4;
		// 密匙
		$key = md5($key ? $key : "hywv3iqyibqrk");
		// 密匙a会参与加解密
		$keya = md5(substr($key, 0, 16));
		// 密匙b会用来做数据完整性验证
		$keyb = md5(substr($key, 16, 16));
		// 密匙c用于变化生成的密文
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
		// 参与运算的密匙
		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);
		// 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
		// 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);
		$result = '';
		$box = range(0, 255);
		$rndkey = array();
		
		// 产生密匙簿
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
		
		// 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
		
		// 核心加解密部分
		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			// 从密匙簿得出密匙进行异或，再转成字符
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
		
		
		if($operation == 'DECODE') {
			// substr($result, 0, 10) == 0 验证数据有效性
			// substr($result, 0, 10) - time() > 0 验证数据有效性
			// substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
			// 验证数据有效性，请看未加密明文的格式
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			// 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
			// 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	} 
	
	//邮件格式验证的函数
	function check_email($email)
	{
		if(!preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/",$email))
		{
			return false;
		}
		else
		return true;
	}

	//验证手机号码
	function check_mobile($mobile)
	{
		if(!empty($mobile) && !preg_match("/^\d{6,}$/",$mobile))
		{
			return false;
		}
		else
		return true;
	}

	/**
	 * 检查身份证
	 * 0失败1成功
	 */
	function getIDCardInfo($IDCard) {
		if (!preg_match("/^[1-9]([0-9a-zA-Z]{17}|[0-9a-zA-Z]{14})$/", $IDCard)) {
			$flag = 0;
		} else {
			if (strlen($IDCard) == 18) {
				$tyear = intval(substr($IDCard, 6, 4));
				$tmonth = intval(substr($IDCard, 10, 2));
				$tday = intval(substr($IDCard, 12, 2));
				if ($tyear > date("Y") || $tyear < (date("Y") - 100)) {
					$flag = 0;
				}
				elseif ($tmonth < 0 || $tmonth > 12) {
					$flag = 0;
				}
				elseif ($tday < 0 || $tday > 31) {
					$flag = 0;

				} else {
					$tdate = $tyear . "-" . $tmonth . "-" . $tday . " 00:00:00";
					if ((time() - mktime(0, 0, 0, $tmonth, $tday, $tyear)) < 18 * 365 * 24 * 60 * 60) {
						$flag = 0;
					} else {
						$flag = 1;
					}
				}

			}
			elseif (strlen($IDCard) == 15) {
				$tyear = intval("19" . substr($IDCard, 6, 2));
				$tmonth = intval(substr($IDCard, 8, 2));
				$tday = intval(substr($IDCard, 10, 2));
				if ($tyear > date("Y") || $tyear < (date("Y") - 100)) {
					$flag = 0;
				}
				elseif ($tmonth < 0 || $tmonth > 12) {
					$flag = 0;
				}
				elseif ($tday < 0 || $tday > 31) {
					$flag = 0;
				} else {
					$tdate = $tyear . "-" . $tmonth . "-" . $tday . " 00:00:00";
					if ((time() - mktime(0, 0, 0, $tmonth, $tday, $tyear)) < 18 * 365 * 24 * 60 * 60) {
						$flag = 0;
					} else {
						$flag = 1;
					}
				}
			}
		}
		return $flag;
	}
	
	//跳转
	function app_redirect($url,$time=0,$msg='')
	{
		if(!defined("SITE_DOMAIN"))
			define("SITE_DOMAIN",get_domain());
		//多行URL地址支持
		$url = str_replace(array("\n", "\r"), '', $url);
		if(empty($msg))
			$msg    =   "系统将在{$time}秒之后自动跳转到{$url}！";
		if (!headers_sent()) {
			// redirect
			if(0===$time) {
				if(substr($url,0,1)=="/")
				{
					header("Location:".SITE_DOMAIN.$url);
				}
				else
				{
					header("Location:".$url);
				}

			}else {
				header("refresh:{$time};url={$url}");
				echo($msg);
			}
			exit();
		}else {
			$str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
			if($time!=0)
				$str   .=   $msg;
			exit($str);
		}
	}
	
	/**
	 * utf8字符转Unicode字符
	 * @param string $char 要转换的单字符
	 * @return void
	 */
	function utf8_to_unicode($char)
	{
		switch(strlen($char))
		{
			case 1:
				return ord($char);
			case 2:
				$n = (ord($char[0]) & 0x3f) << 6;
				$n += ord($char[1]) & 0x3f;
				return $n;
			case 3:
				$n = (ord($char[0]) & 0x1f) << 12;
				$n += (ord($char[1]) & 0x3f) << 6;
				$n += ord($char[2]) & 0x3f;
				return $n;
			case 4:
				$n = (ord($char[0]) & 0x0f) << 18;
				$n += (ord($char[1]) & 0x3f) << 12;
				$n += (ord($char[2]) & 0x3f) << 6;
				$n += ord($char[3]) & 0x3f;
				return $n;
		}
	}

	/**
	 * utf8字符串分隔为unicode字符串
	 * @param string $str 要转换的字符串
	 * @param string $depart 分隔,默认为空格为单字
	 * @return string
	 */
	function str_to_unicode_word($str,$depart=' ')
	{
		$arr = array();
		$str_len = mb_strlen($str,'utf-8');
		for($i = 0;$i < $str_len;$i++)
		{
			$s = mb_substr($str,$i,1,'utf-8');
			if($s != ' ' && $s != '　')
			{
				$arr[] = 'ux'.utf8_to_unicode($s);
			}
		}
		return implode($depart,$arr);
	}

	/**
	 * utf8字符串分隔为unicode字符串
	 * @param string $str 要转换的字符串
	 * @return string
	 */
	function str_to_unicode_string($str)
	{
		$string = str_to_unicode_word($str,'');
		return $string;
	}

	//分词
	function div_str($str)
	{
		require_once APP_ROOT_PATH."system/libs/words.php";
		$words = words::segment($str);
		$words[] = $str;
		return $words;
	}
	function unicode_encode($name) {//to Unicode
		$name = iconv('UTF-8', 'UCS-2', $name);
		$len = strlen($name);
		$str = '';
		for($i = 0; $i < $len - 1; $i = $i + 2) {
			$c = $name[$i];
			$c2 = $name[$i + 1];
			if (ord($c) > 0) {// 两个字节的字
				$cn_word = '\\'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
				$str .= strtoupper($cn_word);
			} else {
				$str .= $c2;
			}
		}
		return $str;
	}

	function unicode_decode($name) {//Unicode to
		$pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
		preg_match_all($pattern, $name, $matches);
		if (!empty($matches)) {
			$name = '';
			for ($j = 0; $j < count($matches[0]); $j++) {
				$str = $matches[0][$j];
				if (strpos($str, '\\u') === 0) {
					$code = base_convert(substr($str, 2, 2), 16, 10);
					$code2 = base_convert(substr($str, 4), 16, 10);
					$c = chr($code).chr($code2);
					$c = iconv('UCS-2', 'UTF-8', $c);
					$name .= $c;
				} else {
					$name .= $str;
				}
			}
		}
		return $name;
	}
	
	/*ajax返回*/
	function ajax_return($data)
	{
			header("Content-Type:text/html; charset=utf-8");
			echo(json_encode($data));
			exit;
	}
	
	function isMobile() {
		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
			return true;
		}
		//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		if (isset ($_SERVER['HTTP_VIA'])) {
			//找不到为flase,否则为true
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		}
		//判断手机发送的客户端标志,兼容性有待提高
		if (isset ($_SERVER['HTTP_USER_AGENT'])) {
			$clientkeywords = array (
					'nokia',
					'sony',
					'ericsson',
					'mot',
					'samsung',
					'htc',
					'sgh',
					'lg',
					'sharp',
					'sie-',
					'philips',
					'panasonic',
					'alcatel',
					'lenovo',
					'iphone',
					'ipod',
					'blackberry',
					'meizu',
					'android',
					'netfront',
					'symbian',
					'ucweb',
					'windowsce',
					'palm',
					'operamini',
					'operamobi',
					'openwave',
					'nexusone',
					'cldc',
					'midp',
					'wap',
					'mobile'
			);
			// 从HTTP_USER_AGENT中查找手机浏览器的关键字
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
				return true;
			}
		}
		//协议法，因为有可能不准确，放到最后判断
		if (isset ($_SERVER['HTTP_ACCEPT'])) {
			// 如果只支持wml并且不支持html那一定是移动设备
			// 如果支持wml和html但是wml在html之前则是移动设备
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
				return true;
			}
		}
	}
	
	//给字符串添加引号
	function str_quot($str,$separator=","){
		//echo $str."<br>";
		$str_arr = explode($separator,$str);
		//print_r($str_arr); exit;
		$str_new = "";
		foreach ($str_arr AS $key => $val){
			if ($str_new == ""){
				$str_new = "'".addslashes($val)."'";
			}else{
				$str_new .= ",'".addslashes($val)."'";
			}
		}

		return $str_new;
	}
	
	function to_timespan($str, $format = 'Y-m-d H:i:s')
	{
		$timezone = intval(app_conf('TIME_ZONE'));
		//$timezone = 8;
		$time = intval(strtotime($str));
		if($time!=0)
		$time = $time - $timezone * 3600;
		return $time;
	}
	
	function date_in($start_date,$end_date,$add_quotation = true){
		$sysc_start_time = to_timespan(to_date(to_timespan($start_date),'Y-m-d'));
		$sysc_end_time = to_timespan(to_date(to_timespan($end_date),'Y-m-d'));
		if ($sysc_end_time == 0)
			$sysc_end_time = $sysc_start_time;

		if ($sysc_start_time == 0)
			$sysc_start_time = $sysc_end_time;

		$str_in = '';
		for($s_date = $sysc_start_time; $s_date <= $sysc_end_time; $s_date += 86400){
			if ($add_quotation){
				if ($str_in == ""){
					$str_in = "'".to_date($s_date,'Y-m-d')."'";
				}else{
					$str_in .= ",'".to_date($s_date,'Y-m-d')."'";
				}
			}else{
				if ($str_in == ""){
					$str_in = to_date($s_date,'Y-m-d');
				}else{
					$str_in .= ",".to_date($s_date,'Y-m-d');
				}
			}
		}
		return $str_in;
	}

	//日期加减
	function dec_date($date,$dec){
		//$sysc_start_time = to_timespan(to_date(to_timespan($date),'Y-m-d')) - $dec * 86400;

		return to_date(to_timespan($date)  - $dec * 86400,'Y-m-d');
	}
	
	//唯一值验证
	function HASH_KEY(){
		if(!es_session::is_set("HASH_KEY")){
			require_once APP_ROOT_PATH."system/utils/es_string.php";
			es_session::set("HASH_KEY",es_string::rand_string(50));
		}
		return es_session::get("HASH_KEY");
	}

	function check_hash_key(){
		if(strim($_REQUEST['fhash'])!="" && md5(HASH_KEY())==md5($_REQUEST['fhash'])){
			return true;
		} else return false;
	}

	function write_timezone($zone='')
	{
		if($zone=='')
		$zone = conf('TIME_ZONE');
			$var = array(
				'0'	=>	'UTC',
				'8'	=>	'PRC',
			);
			
			//开始将$db_config写入配置
			$timezone_config_str 	 = 	"<?php\r\n";
			$timezone_config_str	.=	"return array(\r\n";
			$timezone_config_str.="'DEFAULT_TIMEZONE'=>'".$var[$zone]."',\r\n";
			
			$timezone_config_str.=");\r\n";
			$timezone_config_str.="?>";
		   
			@file_put_contents(get_real_path()."public/timezone_config.php",$timezone_config_str);
	}
	
	//解析URL标签
	// $str = u:shop|acate#index|id=10&name=abc
	function parse_url_tag($str)
	{
		$key = md5("URL_TAG_".$str);
		if(isset($GLOBALS[$key]))
		{
			return $GLOBALS[$key];
		}

		$url = load_dynamic_cache($key);
		$url=false;
		if($url!==false)
		{
			$GLOBALS[$key] = $url;
			return $url;
		}
		$str = substr($str,2);
		$str_array = explode("|",$str);
		$app_index = $str_array[0];
		$route = $str_array[1];
		$param_tmp = explode("&",$str_array[2]);
		$param = array();

		foreach($param_tmp as $item)
		{
			if($item!='')
				$item_arr = explode("=",$item);
			if($item_arr[0]&&$item_arr[1])
				$param[$item_arr[0]] = $item_arr[1];
		}
		$GLOBALS[$key]= url($app_index,$route,$param);
		set_dynamic_cache($key,$GLOBALS[$key]);
			
		return $GLOBALS[$key];
	}

	//显示错误
	function showErr($msg,$ajax=0,$jump='',$stay=0)
	{	
		echo "<script>alert('".$msg."');location.href='".$jump."';</script>";exit;	
	}

	//显示成功
	function showSuccess($msg,$ajax=0,$jump='',$stay=0)
	{
		echo "<script>alert('".$msg."');location.href='".$jump."';</script>";exit;
	}
	
	/**
	 * 验证手机号、电话 400是否正确
	 * @author wqs
	 * @param string phone
	 * @return bool T or F
	 * */
	function isTel($tel,$type='')  
	{  
	  $regxArr = array(  
	  'sj'  =>  '/^(\+?86-?)?(18|15|13)[0-9]{9}$/',  
	  'tel' =>  '/^(010|02\d{1}|0[3-9]\d{2})-\d{7,9}(-\d+)?$/',
	  'tel1' => '/^[0-9]*$/',
	  '400' =>  '/^400(-\d{3,4}){2}$/',  
	  );  
	  if($type && isset($regxArr[$type]))  
	  {  
		return preg_match($regxArr[$type], $tel) ? true:false;  
	  }  
	  foreach($regxArr as $regx)  
	  {  
		if(preg_match($regx, $tel ))  
		{  
		  return true;  
		}  
	  }  
	  return false;  
	}
	
	/**
	 * 设置cookie
	 * @param $param array('user_id' => '用户id','cookie_id' => '缓存ID','time' => '时间','cookie_status' => '是否是cookie')
	 * @return Null
	 */
	function SetCookies($data = array()){
		$_cache_id = empty($data['nid'])?md5("diyou_userid"):$data['nid'];
		if (isset($data['type']) && $data['type'] == 1){
			if ($data["time"]!=""){
			   $_ctime = time()+$data["time"];
			}else{
			   $_ctime = time()+60*60;
			} 
			setcookie($_cache_id,authcode($data['value'].",".time(),"ENCODE"),$_ctime,"/");
		}else{
			if ($data["time"]==""){
				$data["time"] = 60*60;
			}
			$_SESSION[$_cache_id] = authcode($data['value'].",".time(),"ENCODE");
		}
	}

	/**
	 * 获取cookie
	 * @param $param array('session_id' => '缓存ID','time' => '时间','cookie_status' => '是否是cookie')
	 * @return Null
	 */
	function GetCookies($data = array()){
		$_cache_id = empty($data['nid'])?md5("diyou_userid"):$data['nid'];
		$_time = (isset($data['time']) && $data['time']!="")?$data['time']:60*60;
		$_cache = array(0);
		if (isset($data['type']) && $data['type'] == 1){
			$_cache = explode(",",authcode(isset($_COOKIE[$_cache_id])?$_COOKIE[$_cache_id]:"","DECODE"));	
		}else{
			$_cache = explode(",",authcode(isset($_SESSION[$_cache_id])?$_SESSION[$_cache_id]:"","DECODE"));
		}
		return $_cache[0]; 
	}

	/**
	 * 清除cookie
	 * @param $param array('session_id' => '缓存ID')
	 * @return Null
	 */
	function DelCookies($data = array()){
		$_cache_id = empty($data['nid'])?md5("diyou_userid"):$data['nid'];
		setcookie($_cache_id);
		setcookie($_cache_id,"");
		setcookie($_cache_id,"",time()-1);
		setcookie($_cache_id,"",time()-1,'/');
		$_SESSION[$_cache_id] = "";
		
	}

	//解析URL标签
	// $str = u:shop|acate#index|id=10&name=abc
	function parse_wap_url_tag($str)
	{
		$key = md5("WAP_URL_TAG_".$str);
		if(isset($GLOBALS[$key]))
		{
			return $GLOBALS[$key];
		}

		$url = load_dynamic_cache($key);
		$url=false;
		if($url!==false)
		{
			$GLOBALS[$key] = $url;
			return $url;
		}
		$str = substr($str,2);
		$str_array = explode("|",$str);
		$app_index = $str_array[0];
		$route = $str_array[1];
		$param_tmp = explode("&",$str_array[2]);
		$param = array();

		foreach($param_tmp as $item)
		{
			if($item!='')
				$item_arr = explode("=",$item);
			if($item_arr[0]&&$item_arr[1])
				$param[$item_arr[0]] = $item_arr[1];
		}
		$GLOBALS[$key]= wap_url($app_index,$route,$param);
		set_dynamic_cache($key,$GLOBALS[$key]);
		return $GLOBALS[$key];
	}
	function is_mac($mac){
		$mac_addr = explode('-',str_replace(':','-',$mac));
		if(is_array($mac_addr) && count($mac_addr)==6){
			unset($mac_addr);
			return true;
		}
		return false;
	}
	function is_version($version){
		$v_list = explode('.',$version);
		for($i=0;$i<count($v_list);$i++){
			if(!is_int((int)$v_list[$i])){
				return false;
			}
		}
		return true;
	}
	function convert_mac_to_int($mac){
		$mac_addr = explode('-',str_replace(':','-',$mac));
		$last_three_addr='';
		for($i=0;$i<count($mac_addr);$i++){
			echo $i .'=' . $mac_addr[$i]."<br/>";
			if($i>2){
				$last_three_addr .=$mac_addr[$i];
			}
		}
		unset($mac_addr);
		return base_convert($last_three_addr,16,10);
	}
	//封装url
	function url($app_index,$route="index",$param=array())
	{
		$key = md5("URL_KEY_".$app_index.$route.serialize($param));
		if ($GLOBALS['request']['from']!="wap"){
			if(isset($GLOBALS[$key]))
			{
				$url = $GLOBALS[$key];
				return $url;
			}

			$url = load_dynamic_cache($key);
			if($url!==false)
			{
				$GLOBALS[$key] = $url;
				return $url;
			}
		}

		$route_array = explode("#",$route);

		if(isset($param)&&$param!=''&&!is_array($param))
		{
			$param['id'] = $param;
		}

		$module = strtolower(trim($route_array[0]));
		$action = strtolower(trim($route_array[1]));

		if(!$module||$module=='index')$module="";
		if(!$action||$action=='index')$action="";

		if(app_conf("URL_MODEL")==0 || $GLOBALS['request']['from']=="wap")
		{
			//过滤主要的应用url
			if($app_index==app_conf("MAIN_APP"))
				$app_index = "index";
			if(substr($module,0,3)=="uc_"){
				$app_index = "member";
			}

			//原始模式
			$url = APP_ROOT."/".$app_index.".php";
			if($module!=''||$action!=''||count($param)>0) //有后缀参数
			{
				$url.="?";
			}


			if($module&&$module!='')
			$url .= "ctl=".$module."&";
			if($action&&$action!='')
			$url .= "act=".$action."&";
			if(count($param)>0)
			{
				foreach($param as $k=>$v)
				{
					if($k&&$v)
					$url =$url.$k."=".urlencode($v)."&";
				}
			}
			if(substr($url,-1,1)=='&'||substr($url,-1,1)=='?') $url = substr($url,0,-1);
			if ($GLOBALS['request']['from']!="wap"){
				$GLOBALS[$key] = $url;
				set_dynamic_cache($key,$url);
			}
			return $url;
		}
		else
		{
			//重写的默认
			$url = APP_ROOT;

			if($app_index!='index')
			$url .= "/".$app_index;

			if($module&&$module!='')
			$url .= "/".$module;
			if($action&&$action!='')
			$url .= "-".$action;

			if(count($param)>0)
			{
				$url.="/";
				foreach($param as $k=>$v)
				{
					$url =$url.$k."-".urlencode($v)."-";
				}
			}

			//过滤主要的应用url
			if($app_index==app_conf("MAIN_APP"))
			$url = str_replace("/".app_conf("MAIN_APP"),"",$url);

			$route = $module."#".$action;
			switch ($route)
			{
					case "xxx":
						break;
					default:
						break;
			}

			if(substr($url,-1,1)=='/'||substr($url,-1,1)=='-') $url = substr($url,0,-1);


			if($url=='')$url="/";
			$GLOBALS[$key] = $url;
			set_dynamic_cache($key,$url);
			return $url;
		}


	}


