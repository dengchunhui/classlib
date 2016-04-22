<?php
header("content-type:text/html; charset=utf8");
$con=mysql_connect("localhost","root",'');
mysql_query("use bus");
mysql_query("set names utf8");
$xml='
<result>
	<cars lineid = "015800">
		<car>
			<terminal>沪B-71841</terminal>
			<stopdis>3</stopdis>
			<distance>1290</distance>
			<time>190</time>
		</car>
		<car>
			<terminal>11</terminal>
			<vehicle>11</vehicle>
			<stopdis>22</stopdis>
			<distance>333</distance>
			<time>444</time>
		</car>
		<car>
			<terminal>null</terminal>
			<vehicle>null</vehicle>
			<stopdis>null</stopdis>
			<distance>null</distance>
			<time>null</time>
		</car>
	</cars>
</result>
';
$arr=simplexml_load_string($xml);
$lineid=$arr->cars['lineid'];

foreach($arr->cars->car as $key=>$val){
	$val=(array)$val;
	$create_time=time();
	if($val['terminal']!='null'){
		$sql="INSERT INTO `bus_list` ( `create_time`, `lineid`, `terminal`, `stopdis`, `time`, `refer_site`) VALUES ($create_time, '$lineid', '{$val['terminal']}', '{$val['stopdis']}', '{$val['time']}', '43');
";
	mysql_query($sql);
	}
}