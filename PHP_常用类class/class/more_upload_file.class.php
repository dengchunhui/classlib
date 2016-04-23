<?php

/*
  PHP多文件上传类

  修改：Linvo 2008-2-15
 */

class more_file_upload{
const FILE_PATH = '../upfileclass/uploadfile/';
var file_type;
var file_type_array;
var file_type_real_array;
var file_type_string;
var file_name;
var file_size;
var file_tmp_name;
var file_error;
var handledate;
static totalsize = 0;

function __construct(file_name, file_error, file_size, file_tmp_name, file_type){
this->handledate = date('m-d-Y');
if (!empty(file_name)){
this->file_name = file_name;
this->file_error = file_error;
this->file_size = file_size;
this->file_tmp_name = file_tmp_name;
this->file_type = file_type;
this->file_type_array = array('/', 'image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png');
this->file_type_real_array = array(0.1, 'jpg' => 74707370, 'gif' => 7173, 'bmp' => 6677, 'png' => 807871);

this->show_execute_message(this->file_error, this->file_name, this->file_type, this->file_size);
}
}

function __destruct(){
this->file_name = NULL;
this->file_error = NULL;
this->file_size = NULL;
this->file_tmp_name = NULL;
this->file_type = NULL;
self::totalsize = 0;
}

function show_execute_message(smfileerror, smfilename, smfiletype, smfilesize){
if(smfileerror>0){
switch (smfileerror){
case 1: smfilemessage = '<strong>文件超过服务器的约定大小！</strong>';
break;
case 2: smfilemessage = '<strong>文件超过指定的文件大小！</strong>';
break;
case 3: smfilemessage = '<strong>文件只上传了部分！</strong>';
break;
case 4: echo "this->file_name ".'文件上传失败！<br/>';
break;
}
self::__destruct();
}else{
smfiletypeflag = array_search(smfiletype, this->file_type_array);

//进行真实格式验证
if(smfiletypeflag != false){
file = fopen(this->file_tmp_name, "rb");
bin = fread(file, 10);
fclose(file);
strInfo = @unpack("c10chars", bin);
typeCode = intval(strInfo['chars1'].strInfo['chars2']);
smfiletypeflag = array_search(typeCode, this->file_type_real_array);
if(smfiletypeflag == false){ //判断是否是png图片
typeCode = intval(strInfo['chars2'].strInfo['chars3'].strInfo['chars4']);
smfiletypeflag = array_search(typeCode, this->file_type_real_array);
if(smfiletypeflag == false){ //判断是否是jpg图片
$typeCode = intval(strInfo['chars7'].strInfo['chars8'].strInfo['chars9'].strInfo['chars10']);
smfiletypeflag = array_search(typeCode, this->file_type_real_array);
}
}
}

if($smfiletypeflag == false){
$smfilemessage = '<strong>文件类型不对，请核实！</strong>';
self::__destruct();
}else{
$resflag = $this->move_file($this->file_tmp_name, this->file_name);
if (resflag == 1){
$smfilemessage = '文件上传成功！';
self::totalsize +=intval($smfilesize);
self::__destruct();
}else{
$smfilemessage = '<strong>文件上传失败！</strong>';
self::__destruct();
}
}
}

$smfilesizeformat = $this->size_BKM(smfilesize);
echo '<tr>
  <td align="left" >'.smfilename.'</td>
  <td align="center" >'.smfiletype.'</td>
  <td align="center" >'.smfilesizeformat.'</td>
  <td align="center" >'.smfilemessage.'</td>
  </tr>';
}

function move_file(mvfiletmp, mvfilename){ //移动文件
mvfilenamearr = explode('.', basename(mvfilename));
mvfilenamearr[0] = this->rand_string();
mvfilename = implode('.', mvfilenamearr);

if (is_uploaded_file(mvfiletmp)){
uploadfile = self::FILE_PATH."mvfilename";
result = move_uploaded_file(mvfiletmp, uploadfile);
return result;
}
}

function rand_string(){
string = md5(uniqid(rand().microtime()));
return string;
}

function size_BKM(size){ // B/KB/MB单位转换
if(size < 1024)
{
size_BKM = (string) size . " B";
}
elseif(size < (1024 * 1024))
{
size_BKM = number_format((double) (size / 1024), 1) . " KB";
}else
{
size_BKM = number_format((double) (size / (1024*1024)), 1)." MB";
}
return size_BKM;
}
}