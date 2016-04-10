<?php
header("Content-type: text/html; charset=utf-8");
header("pragma:no-cache");
include_once("inc/DB.php");


?>
<!DOCTYPE html>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>添加门店</title>
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
	die('<h2>二维码超时<br>无法添加门店！</h2>'.$wx);
}
$t=intval($_GET["t"]);
$diff = time()-$t;
if($diff>300 || $diff<1)
{
	$mysqli->close();  //关闭数据库链接
	die('<h2>二维码超时<br>无法添加门店！</h2>'.$wx);
}
$querystr = $_SERVER['QUERY_STRING'];
chkLogin("shop_add.php?$querystr");//微信登录授权
//die($querystr);


$boss_wxid = $_COOKIE["openId"];   //业务员微信openId
$nickname =stripslashes($_COOKIE["nickname"]);   //业务员微信昵称
$headimgurl = stripslashes($_COOKIE["headimgurl"]);   //业务员微信头像url地址

if ($boss_wxid == '' || $nickname == '' || $headimgurl == '') 
{
	$mysqli->close();  //关闭数据库链接
	die('<h2>获取微信授权失败<br>无法添加门店！</h2>'.$wx);
}
if(!isset($_GET["t"]))
{
	$mysqli->close();  //关闭数据库链接
	die('<h2>二维码参数错误<br>无法添加门店！</h2>'.$wx);
}
if(!isset($_GET["d"]))
{
	$mysqli->close();  //关闭数据库链接
	die('<h2>二维码参数错误<br>无法添加门店！</h2>'.$wx);
}

$developer_id = $_GET["d"];

	if(inject_check($developer_id)) //openId带注入
	{
		header("location:index.php");
		die();
	}	
	
$sql = "select * from shopdevel where id='$developer_id' and state='正常'";
$result = $mysqli->query($sql);
if($result->num_rows<=0)  //拥有业务员权限
{	
	$mysqli->close();  //关闭数据库链接
	die('<h2>业务员不存在<br>无法添加门店！</h2>');
}
else 
{
	
	$row = $result->fetch_array();
	$developer_wxid = $row["wxid"];
	$result->free();
	$sql = "insert into shop (boss_wxid,boss_name,boss_headimgurl,developer_wxid,state,developer_time) VALUES ('$boss_wxid','$nickname','$headimgurl','$developer_wxid','禁用',now())";
	$mysqli->query($sql);
	if ($mysqli->affected_rows > 0) 
	{  //插入成功
		$sql = "update shopdevel set shop_count=shop_count+1 where wxid='$developer_wxid'";
		$mysqli->query($sql);
		$mysqli->close();  //关闭数据库链接
		die('<h2>添加门店成功<br>请等待业务员审核！</h2>'.$wx);
	} else
	{
		$mysqli->close();  //关闭数据库链接
		die('<h2>添加门店失败<br>请联系业务员重试！</h2>'.$wx);
	}

}	
?>