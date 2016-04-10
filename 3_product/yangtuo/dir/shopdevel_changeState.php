<?php
    header("Content-type: text/html; charset=utf-8");
	header("pragma:no-cache");
	include_once("./inc/DB.php");	
	chkLogin("shopdevel_index.php");//微信登录授权
	checkUsercode("admin"); //判断微信openId是否有管理员操作权限
    //获取参数
	$id = $_POST['id'];  //获取业务员id
	$state = $_POST['state'];   //获取要修改的状态
	if($id==""  ||  $state==""){
		$list = array("msg"=>"0");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	}
	if($state=="0")$state="禁用";
	else $state="正常";
	//修改业务员的状态
	$sql = "update shopdevel set state='$state' where id=$id";
	$mysqli->query($sql);   //执行更新操作
	if($mysqli->affected_rows>0){  //更新操作影响数为0，说明更新成功
		$list = array("msg"=>"1");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	} else {                 //更新操作影响数不为0，说明更新成功
		$list = array("msg"=>"0");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	};
    $mysqli->close();  //关闭数据库
?>
