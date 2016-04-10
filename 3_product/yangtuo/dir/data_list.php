<?php
header("Content-type: text/html; charset=utf-8");
header("pragma:no-cache");
include_once("inc/DB.php");
chkLogin("data_list.php");//微信登录授权

$flag = $_POST['flag'];
$data = array();
$sql = '';
if($flag == 'shopDevel'){		//查询店铺业务员
	checkUsercode("admin"); //判断微信openId是否有管理员操作权限
	$sql = "select * from shopdevel order by add_time DESC";
	$result = $mysqli->query($sql);
	if($result->num_rows==0){
		$data[0] = array("empty"=>true);
	} else {
		$data[0] = array("empty"=>false);
		$i=1;
		while ($row =$result->fetch_array()) {
			$data[1][$i] = array("id"=>$row['id'],"nickname"=>$row['nickname'],"add_time"=>$row['add_time'],"shop_count"=>$row['shop_count'],"headimgurl"=>$row['headimgurl'],"state"=>$row['state'],"tel"=>$row['tel']);
			$i++;
		}
	}
	$result->free();
	$mysqli->close();  //关闭数据库
	die(json_encode($data));  //返回数组数据转成json格式
}
if($flag == 'managerDevel'){		//查询业务经理
	checkUsercode("admin"); //判断微信openId是否有管理员操作权限
	$sql = "select * from managerdevel order by add_time DESC";
	$result = $mysqli->query($sql);
	if($result->num_rows==0){
		$data[0] = array("empty"=>true);
	} else {
		$data[0] = array("empty"=>false);
		$i=1;
		while ($row =$result->fetch_array()) {
			$data[1][$i] = array("id"=>$row['id'],"nickname"=>$row['nickname'],"add_time"=>$row['add_time'],"devel_count"=>$row['devel_count'],"headimgurl"=>$row['headimgurl'],"state"=>$row['state'],"tel"=>$row['tel']);
			$i++;
		}
	}
	$result->free();
	$mysqli->close();  //关闭数据库
	die(json_encode($data));  //返回数组数据转成json格式
}


?>
