<?php
date_default_timezone_set('PRC');
$mysqli = new mysqli("119.145.141.134", "root", "yzwl89923009", "showhealthy",123);
$mysqli->query("SET NAMES utf8");
$system_base ="http://wx.yzwltech.com/showstorebao/";	
$check_key="!QAcfl0c9J8i10l8";
if ($mysqli->connect_errno) {				
	 die("数据库链接错误！");
}


function inject_check($sql_str) { 
	$keyword = 'select|insert|update|delete|%|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile'; 
	$arr = explode( '|', $keyword ); 
	$result = str_ireplace( $arr, '', $sql_str);
	//die($result."<br>" .$sql_str);   
	return !($result==$sql_str); 

}

function chkLogin($furl)
{
	if(!isset($_COOKIE["openId"]))
	{
		header("location:getwxid.php?backurl=".$furl);
		die();
	}
	if($_COOKIE["openId"]=="")
	{
		header("location:getwxid.php?backurl=".$furl);
		die();
	}
	if(inject_check($_COOKIE["openId"])) //openId带注入
	{
		header("location:getwxid.php?backurl=".$furl);
		die();
	}
	if(!isset($_COOKIE["code"]))
	{
		header("location:getwxid.php?backurl=".$furl);
		die();
	}
	if($_COOKIE["code"]!=md5($_COOKIE["openId"]."9rf938ujf"))  //防止伪造openId
	{
		header("location:getwxid.php?backurl=".$furl);
		die();
	}	
	if(!isset($_COOKIE["nickname"]))
	{
		header("location:getwxid.php?backurl=".$furl);
		die();
	}
	if($_COOKIE["nickname"]=="")
	{
		header("location:getwxid.php?backurl=".$furl);
		die();
	}		
}

function setUsercode($user)
{
	$check_key="!QAcfl0c9J8i10l8";
	if(!isset($_COOKIE["openId"]))
	{
		header("location:getwxid.php?backurl=");
		die();
	}
	setcookie($user,md5($_COOKIE["openId"].$user.$check_key));
}
function checkUsercode($user)
{
	$check_key="!QAcfl0c9J8i10l8";
	if(!isset($_COOKIE["openId"]))
	{
		header("location:getwxid.php?backurl=");
		die();
	}
	if(!isset($_COOKIE[$user]))
	{
		header("location:index.php");
		
		die();
	}
	if(md5($_COOKIE["openId"].$user.$check_key)!=$_COOKIE[$user])
	{
		header("location:index.php");
		//die(md5($_COOKIE["openId"].$user.$check_key)."<br>".$_COOKIE[$user]);
		die();
	}
}

?>