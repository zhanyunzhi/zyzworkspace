<?php
header("Content-type: text/html; charset=utf-8");
header("pragma:no-cache");

$cookiesOutTime=time()+3600*24*7;
setcookie('openId','oxpNls9YMo3TOhLS1Kl8bvg0qSTA',$cookiesOutTime);
setcookie('code',md5("oxpNls9YMo3TOhLS1Kl8bvg0qSTA9rf938ujf"),$cookiesOutTime);
setcookie('nickname','oxpNls9YMo3TOhLS1Kl8bvg0qSTA',$cookiesOutTime);
setcookie('headimgurl',stripslashes('oxpNls9YMo3TOhLS1Kl8bvg0qSTA'),$cookiesOutTime);
//die($_COOKIE["openId"]);
include_once("inc/DB.php");
chkLogin("index.php");//微信登录授权
$openId=$_COOKIE["openId"];
$admin="";
//系统管理员
$allowOpenId = ";;;,oxpNls9YMo3TOhLS1Kl8bvg0qSTA,oxpNls6nT6mxch8__fJ-9bkFlKCg";
if( strpos($allowOpenId,$_COOKIE['openId'])>1)
{
	setUsercode('admin');
	$admin="true";
}
	
$shop_developer="";  //店铺业务员
$shop="";	//分店店主
$manager_developer="";	//业务经理
$doctor_developer="";	//医生业务员
$doctor="";	//医生

//拥有店铺业务员权限
$sql = "select wxid from shopdevel where wxid='$openId' and state='正常'";
$result = $mysqli->query($sql);
if($result->num_rows>0)
{	
	$row = $result->fetch_array();
	if($row["wxid"]==$openId)
	{	
		setUsercode('shop_developer');
		$shop_developer="true";
	}
	$result->free();
}
//拥有业务经理权限
$sql = "select wxid from managerdevel where wxid='$openId' and state='正常'";
$result = $mysqli->query($sql);
if($result->num_rows>0)
{
	$row = $result->fetch_array();
	if($row["wxid"]==$openId)
	{
		setUsercode('manager_developer');
		$manager_developer="true";
	}
	$result->free();
}
//拥有医生业务员权限
$sql = "select wxid from doctordevel where wxid='$openId' and state='正常'";
$result = $mysqli->query($sql);
if($result->num_rows>0)
{
	$row = $result->fetch_array();
	if($row["wxid"]==$openId)
	{
		setUsercode('doctor_developer');
		$doctor_developer="true";
	}
	$result->free();
}
//拥有医生权限
$sql = "select wxid from doctor where wxid='$openId' and state='正常'";
$result = $mysqli->query($sql);
if($result->num_rows>0)
{
	$row = $result->fetch_array();
	if($row["wxid"]==$openId)
	{
		setUsercode('doctor');
		$doctor="true";
	}
	$result->free();
}



?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes">
<title>功能菜单目录-卖羊驼</title>
<link rel="stylesheet" type="text/css" href="assets/css/css.css?t=7813">
<!--<script language="javascript" src="assets/js/jquery-1.10.1.min.js"></script>-->


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>
  <div align="center" style="width:100%">
  <img src="<?php echo $_COOKIE["headimgurl"]?>"    class="userHeadimage"/>
  </div>
  <h2 align="center"><?php echo $_COOKIE["nickname"] ; ?>,欢迎使用卖羊驼！<br>
  </h2>
  
  <table width="100%" align="center" id="hblist">
	<tbody>
	<?php if($admin=="true"){ ?> <!-- 管理员-->
	<tr>
<!--		<td height="38" align="center"><a href="list.html?flag=shopDevel" class="index_menu">店铺业务员管理</a></td>-->
		<td height="38" align="center"><a href="shopdevel_index.php" class="index_menu">店铺业务员管理</a></td>
	</tr>
	<tr>
<!--		<td height="38" align="center"><a href="list.html?flag=managerDevel" class="index_menu">业务经理管理</a></td>-->
		<td height="38" align="center"><a href="managerdevel_index.php" class="index_menu">业务经理管理</a></td>
	</tr>
	<?php  } ?>

	<?php if($shop_developer=="true"){ ?><!-- 门店业务员 -->
	<tr>
<!--		<td height="38" align="center"><a href="list.html?flag=shop" class="index_menu">门店管理</a></td>-->
		<td height="38" align="center"><a href="shop_index.php" class="index_menu">门店管理</a></td>
	</tr>
	<?php  } ?>

	<?php if($manager_developer=="true"){ ?><!-- 业务经理 -->
		<tr>
			<td height="38" align="center"><a href="list.html?flag=doctorDevel" class="index_menu">医生业务员管理</a></td>
		</tr>
	<?php  } ?>

	<?php if($doctor_developer=="true"){ ?><!-- 医生业务员 -->
		<tr>
			<td height="38" align="center"><a href="list.html?flag=doctor" class="index_menu">医生管理</a></td>
		</tr>
	<?php  } ?>

	<?php if($doctor=="true"){ ?><!-- 医生 -->
		<tr>
			<td height="38" align="center"><a href="list.html?flag=patient" class="index_menu">患者管理</a></td>
		</tr>
	<?php  } ?>

	<?php
	$sql = "select * from shop where  boss_wxid='$openId' order by developer_time";
	$result = $mysqli->query($sql);
	if($result->num_rows>0)  //拥有分店店主权限
	{	
	?>
	<tr>
		<td height="38" align="center"><a href="#" class="index_menu">我的店铺</a>
		<?php
		while($row = $result->fetch_array())
		{
			if($row["state"]=="正常")
				echo "<a class='shop_menu' href='staff_index.php?id=".$row["id"] ."'>".$row["shop_name"] ."</a>";
			else	
				echo "<a class='disable_shop_menu' href='staff_index.php?id=".$row["id"] ."'>".$row["shop_name"] ."</a>";
		}
		
		setUsercode('shop');
		$shop="true";
		$result->free();
	}

 ?>
		</td>
	</tr>
	<tr>
		<td height="38" align="center"><a href="help_index.php" class="index_menu">使用帮助</a></td>
	</tr>
	
	</tbody>
</table>
  <div align="center">
 
  <br />当前时间: <?php echo date('Y-m-d h:i:s',time());?>
  </div>
</body>
 </html>