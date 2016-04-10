<?php
header("Content-type: text/html; charset=utf-8");
header("pragma:no-cache");
include_once("inc/DB.php");
chkLogin("shop_index.php");//微信登录授权
checkUsercode("shop_developer");
$wxid=$_COOKIE["openId"];
$sql = "select * from shopdevel where wxid ='$wxid'";
$result = $mysqli->query($sql);
if($result->num_rows>0)
{
	$row = $result->fetch_array();
	$developer_id=$row["id"];
	$result->free();
	$mysqli->close();
}
else
{
		header("location:index.php");
		$mysqli->close();
		die();
}

$shop_addurl =$system_base."shop_add.php?t=". time()."&d=".$developer_id;
?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes">
<title>添加门店-赛店宝</title>
<link rel="stylesheet" type="text/css" href="assets/css/css.css?t=12113">
<script src="assets/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.qrcode.min.js"></script>
<script>
	var hburl = "";
	jQuery(function(){
		jQuery('#qrcode').qrcode("<?php echo $shop_addurl ?>");
	})
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>
  <div align="center" class="head_back" onclick="location.href='shop_index.php';">
   <h2 align="center">返回上级页面 </h2>
  </div>
  
<div  class="head_menu">
  <div align="center"><a href="<?php echo $shop_addurl ?>">请门店老板使用微信扫描二维码</a><br />5分钟内扫描有效</div>
</div>
  
  <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td height="300" align="center" valign="middle"><div id="qrcode"></div></td>
        </tr>
    </table>
  <div align="center">
  <br />当前时间: <?php echo date('Y-m-d h:i:s',time());?>
  </div>
</body>
 </html>