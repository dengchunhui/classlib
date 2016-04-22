<?php
header("Content-type: text/html; charset=utf-8");
//x轴数值名，必填，类型为字符串数组
$xAxis_pot=json_encode(array("20150825","20150826","20150827","20150828","20150829","20150830","20150831"));

//y轴名，必填，类型为字符串
$yAxis_title=json_encode("yaxis");

//传入的数据名数组，必填，数据名类型为字符串
$data_name=json_encode(array("线上","线下"));

//传入的数据数组，必填，类型为数组
$date=array(
array(211,545,676,765,123,323,656),
array(343,656,878,321,323,454,545)
);
$data_array=json_encode($date);

//单位名，必填，鼠标划过线条显示数据的单位，类型为字符串
$unit=json_encode(array("元"));

//饼图里的单位数据
$series_name=json_encode("百分比");

//饼图的百分比数据，数据类型为[25,50,15,10]加起来要整等于100
$pie_data_array=json_encode(array(30,70));

include 'chart_demo.html';


        