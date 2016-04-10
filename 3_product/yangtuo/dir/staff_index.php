<?php
header("Content-type: text/html; charset=utf-8");
header("pragma:no-cache");
include_once("inc/DB.php");
chkLogin("index.php");//微信登录授权
checkUsercode("shop");	//判断微信openId是否有门店操作权限
$wxid=$_COOKIE["openId"];
$id = $_GET['id'];
@$page = $_GET['page'];
if($id=='' || $id==null){
	$mysqli->close();
	die('<script>alert("参数错误！");history.go(-1);</script>');
}
if($page=='' || $page==null){
	$page = 'staff';
}
//检查当前店铺是不是当前微信用户的
$sql_shop = "select shop_name,state,developer_wxid from shop where boss_wxid ='$wxid' and id =$id";
$result_shop = $mysqli->query($sql_shop);
$row_shop = '';
if($result_shop->num_rows>0){
	$row_shop = $result_shop->fetch_array();
	$developer_wxid=$row_shop["developer_wxid"];
	$result_shop->free();
	if($row_shop["state"]!="正常")
	{
		$sql_dev = "select developer,tel,nickname from developer where developer_wxid='$developer_wxid'";
		$result_dev = $mysqli->query($sql_dev);
		
		if($result_dev && $result_dev->num_rows>0)
		{
			$row_dev = 	$result_dev->fetch_array();
			$dev_str = "[".$row_dev["developer"] . "(".$row_dev["nickname"] .")". $row_dev["tel"]  ."]";
	
			$result_dev->free();
			die("<script>alert('当前店铺未启用！请联系业务员" . $dev_str . "开通');location.href='index.php';</script>");			
		}
		else
		{
			die('<script>alert("当前店铺状态错误，找对应不到业务员");location.href="index.php";</script>');	
		}
	}
}else{
	$mysqli->close();
	die('<script>alert("当前店铺不属于您！");history.go(-1);</script>');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes">
<title><?php echo $row_shop['shop_name'] ?>-赛店宝</title>
<link rel="stylesheet" type="text/css" href="./css/css.css?t=12113">
<style>
	/*body{margin:0;padding:0;}*/
  .tab ul{padding:0;margin:10px 0;border:1px solid #6e6e6e;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;}
  .tab ul li{width:25%;float:left;display:inline;border-right:1px solid #6e6e6e;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;text-align:center;line-height:30px;list-style:none;}
  .tab ul:last-child{border-right:none;}
  .clear-fix{clear:both;}
  .active{background-color:#AAEA94;color:#ffffff;}
</style>
<!--<script language="javascript" src="js/jquery-1.10.1.min.js"></script>-->
<script>
function go(id,shop_id,tab){
	location.href=tab+"_detail.php?shop_id="+shop_id+"&id=" +id;
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>
 <!-- <div align="center" class="head_back" onclick="location.href='index.php';">
   <h2 align="center">返回上级页面 </h2>
  </div>-->
  <div class="tab">
	<ul>
	  <li onclick="location.href='index.php';">返回</li>
	  <li <?php echo 'onclick="location.href=\'?id='.$id.'&page=staff\';"'; if($page=='staff'){echo 'class="active"';}?>>员工管理</li>
	  <li <?php echo 'onclick="location.href=\'?id='.$id.'&page=order\';"'; if($page=='order'){echo 'class="active"';}?>>订单管理</li>
	  <li <?php echo 'onclick="location.href=\'?id='.$id.'&page=count\';"'; if($page=='count'){echo 'class="active"';}?>>数据统计</li>
	  <div class="clear-fix"></div>
	</ul>
  </div>
  <h2 style="margin:0 0 5px;text-align:center;"><?php echo $row_shop['shop_name'] ?></h2>
 <?php
  if($page=='dev')
  {   //联系业务员
		$sql_dev = "select * from developer where developer_wxid='$developer_wxid'";
		$result_dev = $mysqli->query($sql_dev);
		
		if($result_dev && $result_dev->num_rows>0)
		{
			$row_dev = 	$result_dev->fetch_array();

?>
<h2 align="center">业务员联系方式</h2>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="25%" height="58" align="center">真实姓名</td>
        <td width="79%" align="center"><?php echo $row_dev["developer"] ;?></td>
      </tr>
      <tr>
        <td height="58" align="center">微信名称</td>
        <td align="center"><?php echo $row_dev["nickname"] ;?></td>
      </tr>
      <tr>
        <td height="58" align="center">微信头像</td>
        <td align="center"><img src="<?php echo $row_dev["headimgurl"]; ?>" width="48" height="48"></td>
      </tr>
      <tr>
        <td height="58" align="center">联系电话</td>
        <td align="center"><a href="tel:<?php echo $row_dev["tel"] ;?>"><?php echo $row_dev["tel"] ;?></a><br />支持一键拨号</td>
      </tr>
      <tr>
        <td height="58" align="center">&nbsp;</td>
        <td align="center"></td>
      </tr>
</table>
<?php	
			$result_dev->free();
				
		}
		else
		{
			die('<script>alert("当前店铺状态错误，找对应不到业务员");history.go(-1);</script>');	
		}

?>
<?php	  
   }
?> 
<?php
  if($page=='staff'){   //员工管理
	  $sql = "select * from shop_staff where boss_wxid ='$wxid' and display_shopid=$id order by add_time DESC";
	  $result = $mysqli->query($sql);
?>
	  <div align="right" class="head_menu"><a href="staff_showAdd.php?shop_id=<?php echo $id;?>">添加员工</a></div>
	  <table width="100%" id="hblist">
		<tbody>
		<?php
		if($result->num_rows>0){
		while ($row = $result->fetch_array()){
		?>
		<tr onclick="go(<?php echo $row["id"].','.$id;?>,'staff')">
			<td width="40"><img src="<?php echo $row["staff_headimg"]; ?>"></td>
			<td class="bt1">
			    <div class="hbt1"><?php echo $row["staff_nickname"]."(".$row["staff_name"].")"; ?>
				  <font>状态:<?php echo $row["staff_state"]; ?></font>
			    </div>
				<div class="hbt2">联系电话:<?php echo  $row["staff_tel"]; ?>
				  <font>添加时间:<?php echo date("Y-m-d",strtotime($row["add_time"])); ?></font>
				</div>
				<div class="hbt2">录入订单数:<?php echo  $row["order_count"]; ?>
					<font>登录次数:<?php echo  $row["login_count"]; ?></font>
				</div>
			</td>
		</tr>
		<?php
			}
		}else{
			echo "<tr><td align='center'>暂无数据</td></tr>";
		}
		$result->free();
		$mysqli->close();
?>
	</tbody>
</table>
 <?php } ?>
 <?php
	 if($page=='order'){   //订单管理   一年内的订单
		 $dateMonth = @$_GET['dateMonth'];  //获取月份参数
		 if($dateMonth=='' || $dateMonth==null){		//月份参数不存在，获取当前月份
			 $dateMonth = date('m');
		 }
		 $sql = "select * from customer_order where boss_wxid ='$wxid' and display_shopid=$id and DATE_SUB(CURDATE(), INTERVAL 365 DAY) <= date(add_time) and month(add_time)='$dateMonth' order by add_time DESC";
		 $result = $mysqli->query($sql);
	 ?>
	 <div align="right" class="head_menu">选择月份：<select id="orderDate"></select></div>
	 <table width="100%" id="hblist">
		 <tbody>
		 <?php
		 if($result->num_rows>0){
			 while ($row = $result->fetch_array()){
				 ?>
				 <tr onclick="go(<?php echo $row["order_code"].','.$id;?>,'order')">
					 <!--<td width="40"><img src="<?php /*echo $row["staff_headimg"]; */?>"></td>-->
					 <td class="bt1">
						 <div class="hbt1">订单号:<?php echo $row["order_code"]; ?>
							 <font>添加时间:<?php echo date("Y-m-d",strtotime($row["add_time"])); ?></font>
						 </div>
						 <div class="hbt2">原价:<?php echo  $row["total_price"]; ?>元
							 <font>优惠:<?php echo $row["offer_price"]; ?>元</font>
						 </div>
						 <div class="hbt2">实付:<?php echo  $row["real_price"]; ?>元
							 <!--<font>登录次数:<?php /*echo  $row["login_count"]; */?></font>-->
						 </div>
					 </td>
				 </tr>
				 <?php
			 }
		 }else{
			 echo "<tr><td align='center'>暂无数据</td></tr>";
		 }
		 $result->free();
		 $mysqli->close();
		 ?>
		 </tbody>
	 </table>
 <?php } ?>
 <?php
 if($page=='count'){   //数据统计
	 $dateMonth = @$_GET['dateMonth'];  //获取月份参数
	 if($dateMonth=='' || $dateMonth==null){		//月份参数不存在，获取当前月份
		 $dateMonth = date('m');
	 }
	 $sql = "select sum(count) as total,drug_name,drug_code,drug_image from customer_order_detail where boss_wxid ='$wxid' and display_shopid=$id and DATE_SUB(CURDATE(), INTERVAL 365 DAY) <= date(add_time) and month(add_time)='$dateMonth' GROUP BY drug_code ORDER BY total DESC LIMIT 10";
	 $result = $mysqli->query($sql);
	 ?>
	 <div align="right" class="head_menu">选择月份：<select id="countDate"></select></div>
	 <table width="100%" id="hblist">
		 <tbody>
		 <?php
		 if($result->num_rows>0){
			 while ($row = $result->fetch_array()){
				 ?>
				 <tr>
					 <td width="40"><img src="<?php echo $row["drug_image"]; ?>"></td>
					 <td class="bt1">
						 <div class="hbt1">药品名称:<?php echo $row["drug_name"]; ?>
							 <font>药品条码:<?php echo $row["drug_code"]; ?></font>
						 </div>
						 <div class="hbt2">销售量:<?php echo  $row["total"]; ?>
						 </div>
					 </td>
				 </tr>
				 <?php
			 }
		 }else{
			 echo "<tr><td align='center'>暂无数据</td></tr>";
		 }
		 $result->free();
		 $mysqli->close();
		 ?>
		 </tbody>
	 </table>
 <?php } ?>
  <div align="center">
  <br />当前时间: <?php echo date('Y-m-d h:i:s',time());?><a href="staff_index.php?id=<?php echo "$id&page=dev"; ?>">联系业务员</a>
  </div>
  <script type="text/javascript">
	  var id = "<?php echo $id;?>";
	  var page = "<?php echo $page;?>";

	  var SMonth = new Date().getMonth()+1;  //获取当前月份
		window.onload = function(){
			var oOrderDate = document.getElementById("orderDate");
			var oCountDate = document.getElementById("countDate");
			if(oOrderDate){
				createMonth(oOrderDate,SMonth);
				oOrderDate.onchange = function(){
					filterDate(this.value);
				}
			}
			if(oCountDate){
				createMonth(oCountDate,SMonth);
				oCountDate.onchange = function(){
					filterDate(this.value);
				}
			}
		}
	  function createMonth(obj,nowM){
		  for(var i=0;i<12;i++){
			  obj.options.add(new Option(nowM+"月",nowM));
			  nowM = nowM - 1;
			  if(nowM<=0){nowM=12;}
		  }
		  <?php if(isset($_GET['dateMonth'])){echo "var dataMonth=".$_GET['dateMonth'].";";}?>
		  if(typeof(dataMonth) == undefined){var dataMonth=SMonth;}
		  for(var j=0;j<obj.options.length;j++) {  //选择当前查询的月份
			  if (dataMonth == obj.options[j].value) {
				  obj.options[j].selected = true;
				  return;
			  }
		  }
	  }
	  function filterDate(dateMonth){
		  location.href="?id="+id+"&page="+page+"&dateMonth="+dateMonth;
	  }
  </script>
</body>
 </html>