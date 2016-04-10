<?php
header("Content-type: text/html; charset=utf-8");


?>
<!DOCTYPE html>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>使用帮助-赛店宝</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<link rel="apple-touch-icon-precomposed" href="###">
		<style>
			h2{text-align:center;}
		</style>
	</head>
	<body>
<h2>请长按二维码关注赛店宝公众号</h2>
<h2>商家、员工请关注公众号获得功能菜单<br>
  <img width='80%' src='./images/wx.jpg'></h2>
<p>&nbsp;</p>
 <div align="center">
  当前用户:<?php echo $_COOKIE["openId"]?>
  <br />当前时间: <?php echo date('Y-m-d h:i:s',time());?>
  </div>
</body>
 </html>