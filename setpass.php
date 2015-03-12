<?php
/*
 * Mail xuguocan@gmail.com
 * URL  http://selboo.com
 * date 2014-12-09
 * Last Modified: 2014-12-09
 *
 */
require_once("config.php");

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
	<form class="form-login" role="form" method="POST" action="setpass.php?act=setpass">
	<h2 class="form-signin-heading">找回密码</h2>

	<div class="alert alert-warning" role="alert">新的密码会重新生成并以邮件方式地址发送</div>

	<div class="form-group">
		<label class="col-sm-3">邮箱:</label>
		<div class="col-sm-9">
			<input type="text" name="mail" id="mail" class="form-control" placeholder="邮箱">
			<div class="checkbox"></div>
		</div>
	</div>

	<div class="checkbox"></div>
	<button class="btn btn-primary btn-block" type="submit">重置密码</button>
	</form>
	<form class="form-login" role="form" method="POST" action="index.php">
	<button class="btn btn-info btn-block">登陆</button>
	</form>

</div>


</body>
</html>

<?php
	
$act  = $_GET['act'];
$mail = $_POST['mail'];

$LDAP_USER_DN    = $_SESSION["LDAP_USER_DN"];
$LDP_Connect     = @ldap_connect( $LDAP_HOST, $LDAP_POST );

if ($act == 'setpass') {
	$LDAP_BIND_ADMIN = @ldap_bind($LDP_Connect, $LDAP_DNUS, $LDAP_DNPS);
	
	$Filter="(mail=*)";
	$LDAP_Search=ldap_search($LDP_Connect, $LDAP_DN, $Filter);
	$LDAP_Info = ldap_get_entries($LDP_Connect, $LDAP_Search);

	for ($i=0; $i<count($LDAP_Info); $i++) {
		if ($LDAP_Info[$i]['mail'][0] == $mail) {
			$LDAP_Mail_Cn = $LDAP_Info[$i]['cn'][0];
		}
	}

	if (empty($LDAP_Mail_Cn)) {
		clock('邮箱不存在', 'setpass.php');
	}
	$New_Pass = newpass();
	$LDAP_USER_DN = sprintf("cn=%s,%s",$LDAP_Mail_Cn,$LDAP_DN);

	$VALUES["userPassword"][0] = "{md5}".base64_encode(pack("H*",md5($New_Pass)));
	
	$LDAP_MOD = @ldap_mod_replace($LDP_Connect,$LDAP_USER_DN,$VALUES);
	if ( $LDAP_MOD ) {
		if (mailto($mail, '密码重置', $New_Pass)) {
			clock("密码重置成功，请查看邮件","index.php");
		} else {
			clock("密码重置成功,邮件发送失败","index.php");
		}
	} else {
		clock("密码重置失败，请联系管理员","setpass.php");
	}
}
?>