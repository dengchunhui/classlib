<?php




/**

 * 检查目标文件夹是否存在，如果不存在则自动创建该目录

 *

 * @access      public

 * @param       string      folder     目录路径。不能使用相对于网站根目录的URL

 *

 * @return      bool

 */

function create_dir($folder)

{

    $reval = false;



    if (!file_exists($folder))

    {

        /* 如果目录不存在则尝试创建该目录 */

        @umask(0);



        /* 将目录路径拆分成数组 */

        preg_match_all('/([^\/]*)\/?/i', $folder, $atmp);



        /* 如果第一个字符为/则当作物理路径处理 */

        $base = ($atmp[0][0] == '/') ? '/' : '';



        /* 遍历包含路径信息的数组 */

        foreach ($atmp[1] AS $val)

        {

            if ('' != $val)

            {

                $base .= $val;



                if ('..' == $val || '.' == $val)

                {

                    /* 如果目录为.或者..则直接补/继续下一个循环 */

                    $base .= '/';



                    continue;

                }

            }

            else

            {

                continue;

            }



            $base .= '/';



            if (!file_exists($base))

            {

                /* 尝试创建目录，如果创建失败则继续循环 */

                if (@mkdir(rtrim($base, '/'), 0777))

                {

                    @chmod($base, 0777);

                    $reval = true;

                }

            }

        }

    }

    else

    {

        /* 路径已经存在。返回该路径是不是一个目录 */

        $reval = is_dir($folder);

    }



    clearstatcache();



    return $reval;

}
function create_file($dir,$contents=""){
	$dirs = explode('/',$dir);
	if($dirs[0]==""){
		$dir = substr($dir,1);
	}
	create_dir(dirname($dir));
	@chmod($dir, 0777);
	if (!($fd = @fopen($dir, 'wb'))) {
		$_tmp_file = $dir . DIRECTORY_SEPARATOR . uniqid('wrt');
		if (!($fd = @fopen($_tmp_file, 'wb'))) {
			trigger_error("系统无法写入文件'$_tmp_file'");
			return false;
		}
	}
	fwrite($fd, $contents);
	fclose($fd);
	@chmod($dir, 0777);
	return true;
}
   

/**
 * 10,检测验证码
 * @param $param array('dir' => '地址')
 * @return Null
 */
 function check_valicode($type=""){
 	$msg = "";
 	if($_SESSION['valicode']!=$_POST['valicode']){
		$msg = array("验证码不正确");
	}else{
		if ($type==""){
			$_SESSION['valicode'] = "";
		}
	}
 	return $msg;
 }
 /**
 * 11,XML转数组
 * @param $param array('dir' => '地址')
 * @return Null
 */
 function struct_to_array($item) {                        
  if(!is_string($item)) {                                
    $item = (array)$item;                                
    foreach ($item as $key=>$val){                     
      $item[$key]  =  struct_to_array($val);             
    }                                                    
  }                                                      
  return $item;                                          
}  
function xml_to_array( $xml ) 
{ 
$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/"; 
if(preg_match_all($reg, $xml, $matches)) 
{ 
$count = count($matches[0]); 
for($i = 0; $i < $count; $i++) 
{ 
$subxml= $matches[2][$i]; 
$key = $matches[1][$i]; 
if(preg_match( $reg, $subxml )) 
{ 
$arr[$key] = xml_to_array( $subxml ); 
}else{ 
$arr[$key] = $subxml; 
} 
} 
} 
return $arr; 
} 
// Xml 转 数组, 不包括根键 
function xmltoarray( $xml ) 
{ 
$arr = xml_to_array($xml); 
$key = array_keys($arr); 
return $arr[$key[0]]; 
} 

/**
 * 将URL格式的字符串转化为ID
 *
 * @param String $str
 * @return Array(goods_type, goods_id)
 */
function Url2Key($key,$type) {
	$key = base64_decode ( urldecode ( $key ) );
	return explode ($type, $key );
}


/**
 * 2,判断函数是否存在，不存在则返回false
 */
function check_rank($purview){
	global $_G,$_A;
	$admin_purview = explode(",",$_A['admin_result']['purview']);
	if (in_array("other_all",$admin_purview) || $_A['admin_result']['type_id']==1){
		return true;
	}else if (!in_array($purview,$admin_purview)){
		
		echo "<script>alert('你没有权限');history.go(-1);</script>";exit;
	}
}


//去掉相应的参数
function url_format($url, $format = ''){
	if ($url=="") return "?";
	$_url =  explode("?",$url);
	$_url_for = "";
	if (isset($_url[1]) && $_url[1]!=""){
		$request = $_url[1];
		if ($request != ""){
			$_request = explode("&",$request);
			foreach ($_request as $key => $value){
				$_value = explode("=",$value);
				if (trim($_value[0])!=$format){
					$_url_for ="&" .$value;
				}
			}
		}
		$_url_for = substr($_url_for, 1,strlen($_url_for)); 
	}
	return "?".$_url_for;
}




/** 
* PHP获取字符串中英文混合长度  
* @param $str string 字符串 
* @param $$charset string 编码 
* @return 返回长度，1中文=1位，2英文=1位 
*/  
function strLength($str,$charset='gb2312'){  
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


function modifier($fun,$value,$arr=""){
	global $_G;
	require_once(ROOT_PATH."plugins/magic/modifier.".$fun.".php");
	$_fun = "magic_modifier_".$fun;
	return $_fun($value,$arr,array("_G"=>$_G));
}

function num_big($num)
{
$d = array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖');
$e = array('元','拾','佰','仟','万','拾万','佰万','仟万','亿','拾亿','佰亿','仟亿','万亿');
$p = array('分','角');
$zheng='整'; //追加"整"字
$final = array(); //结果
$inwan=0; //是否有万
$inyi=0; //是否有亿
$len_pointdigit=0; //小数点后长度
$y=0;
if($c = strpos($num, '.')) //有小数点,$c为小数点前有几位数
{ 
$len_pointdigit = strlen($num)-strpos($num, '.')-1; // 判断小数点后有几位数
if($c>13) //简单的错误处理
{
echo "数额太大,已经超出万亿.";
die();
}
elseif($len_pointdigit>2) //$len_pointdigit小数点后有几位
{
echo "小数点后只支持2位.";
die();
}
}
else //无小数点
{
$c = strlen($num);
$zheng = '整';
}
for($i=0;$i<$c;$i++) //处理整数部分
{
$bit_num = substr($num, $i, 1); //逐字读取 左->右
if($bit_num!=0 || substr($num, $i+1, 1)!=0) //当前是零 下一位还是零的话 就不显示
@$low2chinses = $low2chinses.$d[$bit_num];
if($bit_num || $i==$c-1) 
@$low2chinses = $low2chinses.$e[$c-$i-1];
}
for($j=$len_pointdigit; $j>=1; $j--) //处理小数部分
{
$point_num = substr($num, strlen($num)-$j, 1); //逐字读取 左->右
if($point_num != 0)
@$low2chinses = $low2chinses.$d[$point_num].$p[$j-1];
//if(substr($num, strlen($num)-2, 1)==0 && substr($num, strlen($num)-1, 1)==0) //小数点后两位都是0
}
$chinses = str_split($low2chinses,2); //字符串转换成数组
//print_r($chinses);
for($x=sizeof($chinses)-1;$x>=0;$x--) //过滤无效的信息
{
if($inwan==0&&$chinses[$x]==$e[4]) //过滤重复的"万"
{
$final[$y++] = $chinses[$x];
$inwan=1;
}
if($inyi==0&&$chinses[$x]==$e[8]) //过滤重复的"亿"
{
$final[$y++] = $chinses[$x];
$inyi=1;
$inwan=0;
}
if($chinses[$x]!=$e[4]&&$chinses[$x]!=$e[8]) //进行整理,将最后的值赋予$final数组
$final[$y++] = $chinses[$x];
}
$newstring=(array_reverse($final)); //$final为倒数组，$newstring为正常可以使用的数组
$nstring=join($newstring); //数组变成字符串
if(substr($num,-2,1)==0 && substr($num,-1)<>0) //判断原金额角位为0 ? 分位不为0 ?
{ 
$nstring=substr($nstring,0,(strlen($nstring)-4))."零".substr($nstring,-4,4); //这样加一个零字
}
$fen="分";
$fj=substr_count($nstring, $fen); //如果没有查到分这个字
return $nstring=($fj==0)?$nstring.$zheng:$nstring; //就将"整"加到后面
}

function isIdCard($number) {
    //加权因子 
    $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    //校验码串 
    $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    //按顺序循环处理前17位 
    for ($i = 0;$i < 17;$i++) { 
        //提取前17位的其中一位，并将变量类型转为实数 
        $b = (int) $number{$i}; 
 
        //提取相应的加权因子 
        $w = $wi[$i]; 
 
        //把从身份证号码中提取的一位数字和加权因子相乘，并累加 
        $sigma += $b * $w; 
    }
    //计算序号 
    $snumber = $sigma % 11; 
 
    //按照序号从校验码串中提取相应的字符。 
    $check_number = $ai[$snumber];
 
    if ($number{17} == $check_number) {
        return true;
    } else {
        return false;
    }
}

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

function htmlentitydecode($str){
	if(is_array($str)){
		foreach($str as $key => $value) {
			$str[$key] = htmlentitydecode($value);
		}
	}else{
		$str = html_entity_decode($str);
	}
	return $str;
}

function del_file($path){
    if (file_exists($path)){
        if(is_file($path)){
            if(    !@unlink($path)    ){
                $show.="$path,";
            }
        } else{
            $handle = opendir($path);
            while (($file = readdir($handle))!='') {
                if (($file!=".") && ($file!="..") && ($file!="")){
                    if (is_dir("$path/$file")){
                        $show.=del_file("$path/$file");
                    } else{
                        if( !@unlink("$path/$file") ){
                            $show.="$path/$file,";
                        }
                    }
                }
            }
            closedir($handle);

            if(!@rmdir($path)){
                $show.="$path,";
            }
        }
    }
    return $show;
}

//整个网站IP限制
function ip_control_all($arr='') {
	if(!is_array($arr)) return false;
	$server_name = $_SERVER["SERVER_NAME"];
	$user_ip = ip_address(); 
	foreach ($arr as $k => $v) { 
		if($server_name == $k) {
			$ip_arr = explode(',', $v); 
			if(!in_array($user_ip, $ip_arr)) {
				return true;
			}else{
				return false;
			}
		}
	}
}


//单个网站后台设置的IP做限制
function ip_control($allow_ip=""){
	if($allow_ip=="") return false;
	$allow_ip_arr = explode(',', $allow_ip);
	$user_ip = ip_address(); 
	if(!in_array($user_ip, $allow_ip_arr)) {
		return true;
	}else{
		return false;
	}
}


/*
* 判断邮箱是否正确
* @author chenwei
* @param string $email 用于验证的字符串
* @return bool
*/
function is_email($email) {
	if(preg_match("/[a-z'0-9]+([._-][a-z'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+/i",$email)) {
		return true;
	}
	return false;
}



/**
 * 验证是否是正数字
 * @author wqs
 * @param string phone
 * @return bool T or F
 * */
function isNum($num,$type='')  
{  
  $regxArr = array(  
  'int'  =>  '^[1-9]*$',  
  'float' =>  '^[1-9]*.*|0.*[1-9]*$'
  );  
  if($type && isset($regxArr[$type]))  
  {  
    return preg_match($regxArr[$type], $num) ? true:false;  
  }  
  foreach($regxArr as $regx)  
  {  
    if(preg_match($regx, $num ))  
    {  
      return true;  
    }  
  }  
  return false;  
}

/**
 * 验证手机号是否正确
 * @author wqs
 * @param string phone
 * @return bool T or F
 * */
 function is_phone($phone){
    if(preg_match("/^1[3|4|5|8][0-9]\d{8,8}$/",$phone)){
        return true;
    }
    return false;
 }
 
/**
 * 验证是否是汉字 utf8 
 * @author wqs
 * @param string phone
 * @return bool T or F
 * */
 function is_chinese($str){
    if (preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$str)) {
        return true;
    }
    return false;
 }
 

/**
 * SQL ADDSLASHES
 * 适用各PHP版本
 */
function saddslshes($string) {
	if (get_magic_quotes_gpc()) {
		$lastname = stripslashes($string);
	}else{
		$lastname = $string;
	}
	$lastname = mysql_escape_string($lastname);
	//$lastname = mysql_real_escape_string($lastname);
	return $lastname;
}

/**
 * 数据库操作之前统一用这个对数据进行转义
 */
function addslshes_array($string){
	if(is_array($string)) {
		foreach ($string as $key => $val) {
			$string[$key] = saddslshes($val);
		}
	}else{
		$string = saddslshes($string);
	}
	return $string;
}

/**
 *    身份证验证
 *
 *    @param    string    $id
 *    @return   boolean
 */
function is_idcard( $id )
{
    $id = strtoupper($id);
    $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
    $arr_split = array();
    if(!preg_match($regx, $id))
    {
        return FALSE;
    }
    if(15==strlen($id)) //检查15位
    {
        $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
 
        @preg_match($regx, $id, $arr_split);
        //检查生日日期是否正确
        $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
        if(!strtotime($dtm_birth))
        {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    else           //检查18位
    {
        $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
        @preg_match($regx, $id, $arr_split);
        $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
        if(!strtotime($dtm_birth))  //检查生日日期是否正确
        {
            return FALSE;
        }
        else
        {
            //检验18位身份证的校验码是否正确。
            //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
            $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            $sign = 0;
            for ( $i = 0; $i < 17; $i++ )
            {
                $b = (int) $id{$i};
                $w = $arr_int[$i];
                $sign += $b * $w;
            }
            $n  = $sign % 11;
            $val_num = $arr_ch[$n];
            if ($val_num != substr($id,17, 1))
            {
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
    }
}
/**
 * @author wqs
 * @time 2014/7/30
 * @desc 通过身份证判断性别
 * @param 身份证号
 * @return false 1是男 2是女
 */
function get_sex($card_id = 0){
    if(empty($card_id)) return false;
    $chackCard = substr($card_id, 0,17);

    //加权因子
    $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    
    //校验码串
    $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    
    for ($i = 0;$i < 17;$i++) {
        //提取前17位的其中一位，并将变量类型转为实数
        $b = (int) $chackCard{$i};
    
        //提取相应的加权因子
        $w = $wi[$i];
    
        //把从身份证号码中提取的一位数字和加权因子相乘，并累加
        $sigma += $b * $w;
    }
    
    //计算序号
    $number = $sigma % 11;
    
    //按照序号从校验码串中提取相应的字符。
    $check_number = $ai[$number];
    
    //组合号码
    $gather = $chackCard.$check_number;
    if($card_id!==$gather){
        return false;
    }else{
        $area = substr($gather, 0,6);//行政区划
        $myYears = substr($gather, 6,4);//出生年份
        $myMonth = substr($gather, 10,2);//出生月份
        $myDay = substr($gather, 12,2);//出生日
        $mySex = substr($gather, 17);//性别
    }
    //性别验证
    if(($mySex%2)==0){
      return 2;
    }else{
      return 1;
    }
}


/**
 * 根据二进制判断文件类型
 * */
function file_type($filename)
{
	error_reporting(0);
	ini_set('max_execution_time',20000);
	ini_set('memory_limit','512M');
	$exs = '/(\.jpg|\.JPG|\.jpeg|\.JPEG|\.gif|\.GIF|\.png|\.PNG)/i';
	$matches = array(
			'/function\_exists\s*\(\s*[\'|\"](popen|exec|proc\_open|system|passthru)+[\'|\"]\s*\)/i',
			'/(exec|shell\_exec|system|passthru)+\s*\(\s*\$\_(\w+)\[(.*)\]\s*\)/i',
			'/((udp|tcp)\:\/\/(.*)\;)+/i',
			'/preg\_replace\s*\((.*)\/e(.*)\,\s*\$\_(.*)\,(.*)\)/i',
			'/preg\_replace\s*\((.*)\(base64\_decode\(\$/i',
			'/(eval|assert|include|require|include\_once|require\_once)+\s*\(\s*(base64\_decode|str\_rot13|gz(\w+)|file\_(\w+)\_contents|(.*)php\:\/\/input)+/i',
			'/(eval|assert|include|require|include\_once|require\_once|array\_map|array\_walk)+\s*\(\s*\$\_(GET|POST|REQUEST|COOKIE|SERVER|SESSION)+\[(.*)\]\s*\)/i',
			'/eval\s*\(\s*\(\s*\$\$(\w+)/i',
			'/(include|require|include\_once|require\_once)+\s*\(\s*[\'|\"](\w+)\.(jpg|gif|ico|bmp|png|txt|zip|rar|htm|css|js)+[\'|\"]\s*\)/i',
			'/\$\_(\w+)(.*)(eval|assert|include|require|include\_once|require\_once)+\s*\(\s*\$(\w+)\s*\)/i',
			'/\(\s*\$\_FILES\[(.*)\]\[(.*)\]\s*\,\s*\$\_(GET|POST|REQUEST|FILES)+\[(.*)\]\[(.*)\]\s*\)/i',
			'/(fopen|fwrite|fputs|file\_put\_contents)+\s*\((.*)\$\_(GET|POST|REQUEST|COOKIE|SERVER)+\[(.*)\](.*)\)/i',
			'/echo\s*curl\_exec\s*\(\s*\$(\w+)\s*\)/i',
			'/new com\s*\(\s*[\'|\"]shell(.*)[\'|\"]\s*\)/i',
			'/\$(.*)\s*\((.*)\/e(.*)\,\s*\$\_(.*)\,(.*)\)/i',
			'/\$\_\=(.*)\$\_/i',
			'/\$\_(GET|POST|REQUEST|COOKIE|SERVER)+\[(.*)\]\(\s*\$(.*)\)/i',
			'/\$(\w+)\s*\(\s*\$\_(GET|POST|REQUEST|COOKIE|SERVER)+\[(.*)\]\s*\)/i',
			'/\$(\w+)\(\$\{(.*)\}/i'
	);
	if (filesize ( $filename ) > 10000000) continue;
	$fp = fopen ( $filename, 'r' );
	$code = fread ( $fp, filesize ( $filename ) );
	fclose ( $fp );
	if (empty ( $code )) continue;
	foreach ( $matches as $matche ) {
		$array = array ();
		preg_match ( $matche, $code, $array );
		if (! $array) continue;
		if (strpos ( $array [0], "\x24\x74\x68\x69\x73\x2d\x3e" )) continue;
		$len = strlen ( $array [0] );
		if ($len > 10 && $len < 1500) {
			return 'error';//图片内容有木马
			break;
		}
	}
	unset ( $code, $array );

	$file = fopen($filename, "rb");
	$fileSize = filesize($filename);
	if ($fileSize > 512) { // 取头和尾
		$hexCode = bin2hex(fread($file, 512));
		fseek($file, $fileSize - 512);
		$hexCode .= bin2hex(fread($file, 512));
	} else { // 取全部
		$hexCode = bin2hex(fread($file, $fileSize));
	}  
    if(preg_match("/(3c25.*?28.*?29.*?253e)|(3c3f.*?28.*?29.*?3f3e)|(3C534352495054)|(2F5343524950543E)|(3C736372697074)|69(2F7363726970743E)/is", $hexCode)){
        return 'error';//图片内容有木马
    }
    $file = fopen($filename, "rb");
    $bin = fread($file, 2); //只读2字节
    fclose($file);
    $strInfo = @unpack("C2chars", $bin);
    $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
    $fileType = '';
    switch ($typeCode)
    {
        case 7790:
            $fileType = 'exe';
            break;
        case 7784:
            $fileType = 'midi';
            break;
        case 8297:
            $fileType = 'rar';
            break;        
		case 8075:
            $fileType = 'zip';
            break;
        case 255216:
            $fileType = 'jpg';
            break;
        case 7173:
            $fileType = 'gif';
            break;
        case 6677:
            $fileType = 'bmp';
            break;
        case 13780:
            $fileType = 'png';
            break;
        default:
            $fileType = 'unknown: '.$typeCode;
    }

	//Fix
	if ($strInfo['chars1']=='-1' AND $strInfo['chars2']=='-40' ) return 'jpg';
	if ($strInfo['chars1']=='-119' AND $strInfo['chars2']=='80' ) return 'png';
    return $fileType; 
}

//把十六进制颜色转换为rgb色
function hex2rgb($hexColor){ 
$color = str_replace('#', '', $hexColor); 
if (strlen($color) > 3) { 
    $rgb = array( 
    'r' => hexdec(substr($color, 0, 2)), 
    'g' => hexdec(substr($color, 2, 2)), 
    'b' => hexdec(substr($color, 4, 2)) 
    ); 
} else {
    $color = str_replace('#', '', $hexColor); 
    $r = substr($color, 0, 1) . substr($color, 0, 1); 
    $g = substr($color, 1, 1) . substr($color, 1, 1); 
    $b = substr($color, 2, 1) . substr($color, 2, 1); 
    $rgb = array( 
    'r' => hexdec($r), 
    'g' => hexdec($g), 
    'b' => hexdec($b) 
    ); 
}

return $rgb; 
}
//金额将,转换为数值
function IntFormat($money){
        return intval(str_replace(",","",$money));
    }
	
	

?>