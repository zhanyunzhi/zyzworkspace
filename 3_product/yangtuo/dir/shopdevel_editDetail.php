<?php
    header("Content-type: text/html; charset=utf-8");
	header("pragma:no-cache");
	include_once("./inc/DB.php");	
	chkLogin("shopdevel_index.php");//微信登录授权
	checkUsercode("admin"); //判断微信openId是否有管理员操作权限
    //获取参数
	$id = $_POST['id'];  //获取业务员id
	$name = $_POST['name'];   //获取业务员真实姓名
	$tel = $_POST['tel'];   //获取业务员联系电话
	$remark = $_POST['remark'];   //获取业务员备注信息
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
	if(inject_check($name))
	{
		$list = array("msg"=>"0");
 		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	}
	if(inject_check($remark))
	{
		$list = array("msg"=>"0");
 		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	}
	//修改业务员的信息
	$sql = "update shopdevel set name='$name',tel='$tel',remark='$remark',edit_time=now() where id=$id";
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
