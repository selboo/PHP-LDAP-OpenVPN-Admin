<?php
/*
 * Mail xuguocan@gmail.com
 * URL  http://selboo.com
 * date 2014-12-05
 * Last Modified: 2014-12-05
 *
 */
require_once("config.php");

$LDAP_HOST = $hwl_cfg[LDAP_HOST];
$LDAP_POST = $hwl_cfg[LDAP_POST];
$LDAP_DN   = $hwl_cfg[LDAP_DN];
$LDAP_DNUS = $hwl_cfg[LDAP_DNUS];
$LDAP_DNPS = $hwl_cfg[LDAP_DNPS];

$UserName = $_POST['user'];
$PassWord = $_POST['pass'];

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

<?php
if ( empty( $UserName) or empty( $PassWord ) ) {
	clock("用户名和密码不能为空","/pass/");
}
// 链接
$LDP_Connect = @ldap_connect( $LDAP_HOST, $LDAP_POST );

if ( !$LDP_Connect ) {
	alert('LDAP 服务器链接失败...');
	return 0;
} else {
	$LDAP_USER_DN  = sprintf("cn=%s,%s",$UserName,$LDAP_DN);
	ldap_set_option($LDP_Connect, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($LDP_Connect, LDAP_OPT_REFERRALS, 0);
	// 校验当前用户名和密码
	$LDAP_BING_USER = @ldap_bind($LDP_Connect, $LDAP_USER_DN, $PassWord);
	if ( !$LDAP_BING_USER ) {
		clock("用户名和密码错误","/pass/");
	} else {
		$LDAP_SEARCH_SR = ldap_search($LDP_Connect, $LDAP_USER_DN, "cn=*");
		$LDAP_EMAIL_US  = ldap_get_entries($LDP_Connect, $LDAP_SEARCH_SR);
		$_SESSION["user_status"]  = 1;
		$_SESSION["UserName"]     = $UserName;
		$_SESSION["Email"]        = $LDAP_EMAIL_US[0]['mail'][0];
		$_SESSION["LDAP_USER_DN"] = $LDAP_USER_DN;
		echo "<script> parent.location.href=\"main.php\";</script>";
	}

}


?>
