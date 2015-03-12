<?php
/*
 * Mail xuguocan@gmail.com
 * URL  http://selboo.com
 * date 2014-12-05
 * Last Modified: 2014-12-09
 *
 */
require_once("config.php");
login_status();

$LDAP_HOST = $hwl_cfg[LDAP_HOST];
$LDAP_POST = $hwl_cfg[LDAP_POST];
$LDAP_DN   = $hwl_cfg[LDAP_DN];
$LDAP_DNUS = $hwl_cfg[LDAP_DNUS];
$LDAP_DNPS = $hwl_cfg[LDAP_DNPS];

session_set_cookie_params(3600);
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title></title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/signin.css" rel="stylesheet">
</head>

<body>

<div class="container">
	<form class="form-main" role="form" method="POST" action="main.php?act=mod">
	<h2 class="form-signin-heading">用户信息</h2>
	<div class="form-group">
		<label class="col-sm-3">用户:</label>
		<div class="col-sm-9">
			<input type="text" name="user" id="user" class="form-control" readonly value="<?php echo $_SESSION["UserName"];?>">
			<div class="checkbox"></div>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3">邮箱:</label>
		<div class="col-sm-9">
			<input type="text" name="mail" id="mail" class="form-control" value="<?php echo $_SESSION["Email"];?>">
			<div class="checkbox"></div>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3">新密码:</label>
		<div class="col-sm-9">
			<input type="password" name="pwd1" id="pwd1" class="form-control" placeholder="新密码">
			<div class="checkbox"></div>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3">确认密码:</label>
		<div class="col-sm-9">
			<input type="password" name="pwd2" id="pwd2" class="form-control" placeholder="确认密码">
			<div class="checkbox"></div>
		</div>
	</div>

	<button class="btn btn-success btn-block" type="submit">修改</button>
	</form>


	<form class="form-main" role="form" method="POST" action="main.php?act=vpn">
	<h2 class="form-signin-heading">OpenVPN</h2>
	<div class="form-group">
		<label class="col-sm-3">VPN密码:</label>
		<div class="col-sm-9">
			<input type="text" name="vpnpass" id="vpnpass" class="form-control" value="" placeholder="包括解压密码" >
			<div class="checkbox"></div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3">二维码:</label>
		<div class="col-sm-9">
			<img src="openvpn.png" class="img-thumbnail" alt="Responsive image">
			<div class="checkbox"></div>
		</div>
	</div>
	<button class="btn btn-primary btn-block" type="submit">发送配置文件</button>
	</form>


	<form class="form-main" role="form" method="POST" action="main.php?act=logout">
	<button class="btn btn-warning btn-block" type="submit">退出</button>
	</form>
</div>

</body>
</html>

<?php

$pwd1 = $_POST['pwd1'];
$pwd2 = $_POST['pwd2'];
$mail = $_POST['mail'];
$vpnp = $_POST['vpnpass'];
$act  = $_GET['act'];

$LDAP_USER_DN = $_SESSION["LDAP_USER_DN"];
$LDP_Connect  = @ldap_connect( $LDAP_HOST, $LDAP_POST );


if ($act == 'mod') {

	if ( !empty($pwd1) or !empty($pwd2) ) {
		if ($pwd1 == $pwd2) {
			$LDAP_BIND_ADMIN = @ldap_bind($LDP_Connect, $LDAP_DNUS, $LDAP_DNPS);
			if ( !$LDAP_BIND_ADMIN ) {
				clock("用户名和密码错误","/pass/");
			} else {
				$VALUES["userPassword"][0] = "{md5}".base64_encode(pack("H*",md5($pwd1)));
				$VALUES["mail"][0]         = $mail;
				$LDAP_MOD = @ldap_mod_replace($LDP_Connect,$LDAP_USER_DN,$VALUES);
				if ( $LDAP_MOD ) {
					$_SESSION["user_status"]  = 0;
					clock("修改成功，请重新登陆","/pass/");
				} else {
					clock("修改失败，请重新修改该","main.php");
				}
			}
		} else {
			clock("两次密码不一致","main.php");
		}
	} else {
		clock("密码不能为空","main.php");
	}
}
if ($act == 'vpn') {
	$UserName = $_SESSION["UserName"];
	$UserMail = $_SESSION["Email"];
	$OvpnPass = $vpnp;
	$Ovpnsubj = "OpenVPN 配置文件";
	$Ovpnbody = "解压后放到 Openvpn安装目录config下";
	$OvpnFile = "openvpn/" . $UserName . ".rar";

	function senmail($UserMail, $Ovpnsubj, $Ovpnbody, $OvpnFile) {
		if (mailto($UserMail, $Ovpnsubj, $Ovpnbody, $OvpnFile)) {
			clock("发送成功,请查收邮件...","main.php");
		} else {
			clock("发送失败,请联系管理员...","main.php");
		}
	}

	if (!preg_match("/^[a-zA-Z0-9]{6,18}$/", $OvpnPass)) {
		clock("密码必须是英文或者字母，且长度必须是6-18字符","main.php");
	}

	$keys = openkey($UserName, $UserMail, $OvpnPass);
	if ($keys == 'ok') {
		senmail($UserMail, $Ovpnsubj, $Ovpnbody, $OvpnFile);
	} else {
		clock("生成失败,请联系管理员...","main.php");
	}
}
if ($act == 'logout') {
	loginout('退出成功');
}

?>