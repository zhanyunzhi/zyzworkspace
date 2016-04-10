<?php
    header("Content-type: text/html; charset=utf-8");
	header("pragma:no-cache");
	include_once("./inc/DB.php");	
	chkLogin("shop_index.php");//微信登录授权
	checkUsercode("developer");
    //获取参数
	$id = $_POST['id'];  //获取店员id
	$staff_name = $_POST['staff_name'];   //获取店员名称
	$staff_tel = $_POST['staff_tel'];   //获取店员联系电话
	$staff_address = $_POST['staff_address'];   //获取店员联系地址
	
//	$id=3;
//	$shop_name="森林大药房";
//	$boss_name="LG";
//	$boss_tel="12312312323";
//	$shop_address="江湾路18号11";
	
	if($id==""){
		$list = array("msg"=>"0");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	}
	if(inject_check($id))
	{
		$list = array("msg"=>"0");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	}
	if(inject_check($staff_name))
	{
		$list = array("msg"=>"0");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	}
	if(inject_check($staff_tel))
	{
		$list = array("msg"=>"0");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	}
	if(inject_check($staff_address))
	{
		$list = array("msg"=>"0");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	}
	//修改店员的信息
	$sql = "update shop_staff set staff_name='$staff_name',staff_tel='$staff_tel',staff_address='$staff_address',edit_time=now() where id=$id";
	//die($sql);
	$mysqli->query($sql);   //执行更新操作
	if($mysqli->affected_rows>0){  //更新操作影响大于0，说明更新成功(如果更新的记录和原记录相同，即时语句操作成功也返回0)
		$list = array("msg"=>"1");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	} else {                 //更新操作影响数不为0，说明更新失败
		$list = array("msg"=>"00");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	};
    $mysqli->close();  //关闭数据库
?>
