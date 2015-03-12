<?php
/*
 * Mail xuguocan@gmail.com
 * URL  http://selboo.com
 * date 2014-12-05
 * Last Modified: 2014-12-05
 *
 */

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
	<form class="form-login" role="form" method="POST" action="login.php">
	<h2 class="form-signin-heading">请登录</h2>
	<div class="form-group">
		<label class="col-sm-3">用户:</label>
		<div class="col-sm-9">
			<input type="text" name="user" id="user" class="form-control" placeholder="用户名">
			<div class="checkbox"></div>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3">密码:</label>
		<div class="col-sm-9">
			<input type="password" name="pass" id="pass" class="form-control" placeholder="密码">
			<div class="checkbox"></div>
		</div>
	</div>

	<div class="checkbox"></div>
	<button class="btn btn-primary btn-block" type="submit">登陆</button>
	</form>
	<form class="form-login" role="form" method="POST" action="setpass.php">
	<button class="btn btn-danger btn-block">重置密码</button>
	</form>
</div>

</body>
</html>