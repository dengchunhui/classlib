<?php
//���뷢���ʼ���
require("smtp.php"); 
//ʹ��163���������
$smtpserver = "smtp.163.com";
//163����������˿� 
$smtpserverport = 25;
//���163�����������˺�
$smtpusermail = "deng649578964@163.com";
//�ռ�������
$smtpemailto = "649578964@qq.com";
//��������˺�(ȥ��@163.com)
$smtpuser = "deng649578964";//SMTP���������û��ʺ� 
//�����������
$smtppass = "XYZDENG649578964"; //SMTP���������û�����

//�ʼ����� 
$mailsubject = "�����ʼ�����1";
//�ʼ����� 
$mailbody = "PHP+MySQLADSADASDA";
//�ʼ���ʽ��HTML/TXT��,TXTΪ�ı��ʼ� 
$mailtype = "TXT";
//�������һ��true�Ǳ�ʾʹ�������֤,����ʹ�������֤. 
$smtp = new smtp($smtpserver,$smtpserverport,TRUE,$smtpuser,$smtppass);
//�Ƿ���ʾ���͵ĵ�����Ϣ 
$smtp->debug = TRUE;
//�����ʼ�
$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype); 