<!DOCTYPE html>
<html>
<head>
	<meta charser="UTF-8" />
	<script type="text/javascript" src="formcheck.js"></script>
	<title>APIのテスト</title>
</head>
<body>
<?php
date_default_timezone_set('Asia/Tokyo');
echo date("Y/m/d H:i:s");

//ログイン処理を実行する
include("oauth_test.php");
$user='';
if (!empty($_POST['name'])){
    $user=htmlspecialchars($_POST['name']);
}
$passwd='';
if (!empty($_POST['passwd'])){
    $passwd=htmlspecialchars($_POST['passwd']);
}
if (!empty($user)){
    $ret = execOAuth2($user, $passwd, $json);
}

?>
<form id="form" method="POST" action="/shibaTest/testPHP.php" onSubmit="return check()">
<table>
<tr>
 <td>名前：</td>
 <td><input type=text id="name" name="name"></td>
</tr>
<tr>
 <td>パスワード：</td>
 <td><input type=password id="passwd" name="passwd"></td>
</tr>
<tr>
 <td></td>
 <td>
  <input type=submit value=" 送信 ">
 </td>
</tr>
</table>
</form>
</body>
</html>



