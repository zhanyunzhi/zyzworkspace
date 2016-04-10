<?php
header("Content-type: text/html; charset=utf-8");
header("pragma:no-cache");
include_once("inc/DB.php");


?>
<!DOCTYPE html>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>添加店铺业务员</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<link rel="apple-touch-icon-precomposed" href="###">
		<style>
			h2{text-align:center;}
		</style>
	</head>
	
<?php
$wx = "<h2>请长按二维码关注赛店宝公众号<br><img width='80%' src='assets/images/wx.jpg'></h2>";
if(!isset($_GET["t"]))
{
	die("<h2>二维码超时<br>无法添加业务员</h2>$wx");
}
$t=intval($_GET["t"]);
$diff = time()-$t;
if($diff>300 || $diff<1)
{
	$mysqli->close();  //关闭数据库链接
	die("<h2>二维码超时<br>无法添加业务员</h2>$wx");
}
chkLogin("shopdevel_add.php?t=$t");//微信登录授权

$wxid = $_COOKIE["openId"];   //业务员微信openId
$nickname =stripslashes($_COOKIE["nickname"]);   //业务员微信昵称
$headimgurl = stripslashes($_COOKIE["headimgurl"]);   //业务员微信头像url地址

if ($wxid == '' || $nickname == '' || $headimgurl == '')
{
	$mysqli->close();  //关闭数据库链接
	die("<h2>获取微信授权失败<br>无法添加业务员！</h2>$wx");
}

$sql = "select id from shopdevel where wxid='$wxid'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0)  //数据库中已经存在当前业务员
{   
	$mysqli->close();  //关闭数据库链接
	die('<h2>您已经是业务员!<br>请不要重复添加！</h2>'.$wx);
} 
else 
{
	$sql = "insert into shopdevel (wxid,nickname,headimgurl,state,add_time,shop_count) VALUES ('$wxid','$nickname','$headimgurl','禁用',now(),'0')";
	$mysqli->query($sql);
	if ($mysqli->affected_rows > 0) 
	{  //插入成功
		$mysqli->close();  //关闭数据库链接
		die('<h2>添加业务员成功<br>请等待管理员审核！</h2>'.$wx);
	} else
	{
		$mysqli->close();  //关闭数据库链接
		die('<h2>添加业务员失败<br>请联系管理员重试！</h2>'.$wx);
	}
}
	
?>