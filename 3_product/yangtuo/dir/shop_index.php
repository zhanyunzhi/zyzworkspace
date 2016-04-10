<?php
header("Content-type: text/html; charset=utf-8");
header("pragma:no-cache");
include_once("inc/DB.php");
chkLogin("shop_index.php");//微信登录授权
checkUsercode("shop_developer");

?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes">
<title>门店管理-赛店宝</title>
<link rel="stylesheet" type="text/css" href="assets/css/css.css?t=873">
<script language="javascript" src="assets/js/jquery-1.10.1.min.js"></script>
 <script>
function go(id)
{
	location.href="shop_detail.php?id=" +id;
}
 </script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>
  <div align="center" class="head_back" onclick="location.href='index.php';">
   <h2 align="center">返回上级页面 </h2>
  </div>
  
<div align="right" class="head_menu"><a href="shop_code.php">添加门店</a></div>
   <?php 

$wxid=$_COOKIE["openId"];
$sql = "select * from shop where developer_wxid ='$wxid' order by developer_time DESC";
$result = $mysqli->query($sql);
	
?>

  <table width="100%" id="hblist">
	<tbody>
	<?php 
	if($result->num_rows>0){
           $i=1;
    while ($row = $result->fetch_array()){
	?>
	<tr onclick="go(<?php echo $row["id"]?>)">
		<td width="40"><img src="<?php echo $row["boss_headimgurl"]; ?>"></td>
		<td class="bt1">
			<div class="hbt1"><?php echo $row["shop_name"]."(".$row["boss_name"].")"; ?>
				<font>状态:<?php echo $row["state"]; ?></font>
			</div>
			<div class="hbt2">联系电话:<?php echo  $row["boss_tel"]; ?>
				<font>发展时间:<?php echo date("Y-m-d",strtotime($row["developer_time"])); ?></font>
			</div>
			<div class="hbt2"><?php echo  $row["shop_address"]; ?></div>
		</td>
	</tr>
	<?php 
		}
	}
	$result->free();
	$mysqli->close();
?>

	</tbody>
</table>
  <div align="center">
  <br />当前时间: <?php echo date('Y-m-d h:i:s',time());?>
  </div>
</body>
 </html>