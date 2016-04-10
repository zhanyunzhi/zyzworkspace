<?php
header("Content-type: text/html; charset=utf-8");
header("pragma:no-cache");
$appid = "wxc1a9ed299ff1f9b3";
$secret = "d94d37fc34eb9acb56d64e39a540ce5f";
$cookiesOutTime=time()+3600*24*7;
 //二次回调
$selfurl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
$id="0000";
if(isset($_GET["id"])) $id = $_GET["id"];
$backurl = "";
if(isset($_GET["backurl"]))
{
   $backurl = $_GET["backurl"];
}
else
{
  $backurl = "";
} 
if(isset($_GET["code"]) )    //二次回调，验证通过之后，获得openid
{ 
	$code =$_GET["code"];
	
	$url2 ='https://api.weixin.qq.com/sns/oauth2/access_token?appid='. $appid  .'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
	$data = json_decode(file_get_contents($url2));
	$url3 ="https://api.weixin.qq.com/sns/userinfo?access_token=". $data->access_token."&openid=".$data->openid."&lang=zh_CN";
	$datauser = json_decode(file_get_contents($url3));
	
	
	setcookie('openId',$datauser->openid,$cookiesOutTime);
	setcookie('code',md5($datauser->openid."9rf938ujf"),$cookiesOutTime);
	setcookie('nickname',$datauser->nickname,$cookiesOutTime);
	setcookie('headimgurl',stripslashes($datauser->headimgurl),$cookiesOutTime);
	
	
	if($backurl !="")
		$url = $backurl ;
	else
	$url = 'index.php';
	
	header("location: ".$url."");
}
else  //自动进入授权页面
{
	$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=". $appid ."&redirect_uri=" .
		   $selfurl."&response_type=code&scope=snsapi_base,snsapi_userinfo&state=123#wechat_redirect";
	echo "<a href='". $url."'>自动进入点击授权</a>";
	header("location: ".$url."");
}
?>