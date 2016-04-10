<?php
header("Content-type: text/html; charset=utf-8");
header("pragma:no-cache");
include_once("inc/DB.php");


?>
<!DOCTYPE html>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>添加店员</title>
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
$wx = "<h2>请长按二维码关注赛店宝公众号<br><img width='80%' src='./images/wx.jpg'></h2>";
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
chkLogin("staff_add.php?$querystr");//微信登录授权


$staff_wxid = $_COOKIE["openId"];   //店员微信openId
$nickname =stripslashes($_COOKIE["nickname"]);   //店员微信昵称
$headimgurl = stripslashes($_COOKIE["headimgurl"]);   //店员微信头像url地址

if ($staff_wxid == '' || $nickname == '' || $headimgurl == '')
{
	$mysqli->close();  //关闭数据库链接
	die('<h2>获取微信授权失败<br>无法添加店员！</h2>'.$wx);
}
if(!isset($_GET["shop_id"]))
{
	$mysqli->close();  //关闭数据库链接
	die('<h2>二维码参数错误<br>无法添加店员！</h2>'.$wx);
}

//$boss_wxid = $_GET["boss"];
$shop_id = $_GET["shop_id"];
	if(inject_check($boss_wxid)) //openId带注入
	{
		header("location:index.php");
		die();
	}	
//检查当前店铺是否是当前$boss_wxid老板的
$sql = "select boss_wxid from shop where id=$shop_id and state='正常'";
$result = $mysqli->query($sql);
$boss_wxid = "";   //店铺老板微信id
if($result->num_rows<=0)  //拥有店铺老板权限
{	
	$mysqli->close();  //关闭数据库链接
	die('<h2>店铺老板不存在<br>无法添加店员！</h2>');
}
else {
	$row = $result->fetch_array();
	$boss_wxid = $row['boss_wxid'];
	$result->free();
}
if($boss_wxid == "")
{
	$mysqli->close();  //关闭数据库链接
	die('<h2>找不到店铺老板<br>无法添加店员！</h2>'.$wx);
}
//检查是否已经在当前老板的当前店铺添加过
$sql = "select id from shop_staff where staff_wxid='$staff_wxid' and display_shopid=$shop_id and boss_wxid='$boss_wxid'";

$result = $mysqli->query($sql);
if ($result->num_rows > 0)  //数据库中已经存在当前店员（当前老板的当前店铺添加过）
{
	$mysqli->close();  //关闭数据库链接
	die('<h2>您已经是当前店铺的店员!<br>请不要重复添加！</h2>'.$wx);
}

$sql = "insert into shop_staff (staff_wxid,staff_nickname,staff_headimg,boss_wxid,staff_state,add_time,display_shopid,login_count,order_count) VALUES ('$staff_wxid','$nickname','$headimgurl','$boss_wxid','禁用',now(),$shop_id,0,0)";
	$mysqli->query($sql);
	if ($mysqli->affected_rows > 0) 
	{  //插入成功
		//$sql = "update developer set shop_count=shop_count+1 where developer_wxid='$developer_wxid'";
		//$mysqli->query($sql);
		$mysqli->close();  //关闭数据库链接
		die('<h2>添加店员成功<br>请等待店铺老板审核！</h2>'.$wx);
	} else
	{
		$mysqli->close();  //关闭数据库链接
		die('<h2>添加店员失败<br>请联系店铺老板重试！</h2>'.$wx);
	}

	
?>