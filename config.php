<?php
/*
 * Mail xuguocan@gmail.com
 * URL  http://selboo.com
 * date 2014-12-05
 * Last Modified: 2014-12-09
 *
 */
require("PHPMailer/class.phpmailer.php");
require("PHPMailer/class.smtp.php");

$hwl_cfg = array(
	'LDAP_HOST' => '127.0.0.1',
	'LDAP_POST' => '389',
	'LDAP_DN'   => 'dc=test,dc=com',
	'LDAP_DNUS' => 'cn=root,dc=test,dc=com',
	'LDAP_DNPS' => '123456',
);

function mailto($fromail, $Sub, $bod, $file='Null') {
	$mail = new PHPMailer();
	$address = "root@test.com";
	$mail->IsSMTP();
	$mail->Host = "smtp.test.com"; 
	$mail->SMTPAuth = true;
	$mail->Username = "root@test.com";
	$mail->Password = "123456";
	$mail->From = "root@test.com";
	$mail->FromName = "test";
	$mail->AddAddress("$fromail", "");
	$mail->IsHTML(true);
	$mail->CharSet = "UTF-8";
	$mail->Subject = "$Sub";
	$mail->Body = "$bod";
	if ($file != 'Null') {
		$mail->AddAttachment($file, $file);
	}
	if($mail->Send()) {
		return True;
	} else {
		return False;
	}
}

function newpass() {
	$str= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"; 
	srand((double)microtime()*1000000);
	for($i=0; $i<mt_rand(10,16); $i++) {
		$rand .= $str[rand()%strlen($str)];
	}
	return $rand;
}

function alert($message) {
	print "$message";
}

function clock($txt,$patch='')
{
	echo "<script langage=javascript>alert('$txt');window.location.href='$patch';</script>";
	exit;
}

function login_status()
{
	session_start();
	if($_SESSION["user_status"] == '1'){
	} else {
		clock('Time out...', "/pass/");
	}
}

function loginout($txt)
{
	session_start();
	$_SESSION = array();
	session_destroy();
	$_SESSION["user_status"] = 0;
	echo "<script langage=javascript>alert('$txt');window.location.href='/pass/';</script>";
	exit;
}

function openkey($name,$mail,$pass)
{
	system("LANG=en_US.UTF-8 && python p.py $name $mail $pass && echo ok || echo no", $comd);
	return $comd;
}
?>
