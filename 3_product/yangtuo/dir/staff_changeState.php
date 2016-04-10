<?php
    header("Content-type: text/html; charset=utf-8");
	header("pragma:no-cache");
	include_once("./inc/DB.php");	
	chkLogin("index.php");//微信登录授权
	checkUsercode("shop"); //判断微信openId是否有管理员操作权限
    //获取参数
	$id = $_POST['id'];  //店员id
	$state = $_POST['state'];   //获取要修改的状态
	if($id==""  ||  $state==""){
		$list = array("msg"=>"0");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	}
	if($state=="0")$state="禁用";
	else $state="正常";
	//启用店员的时候，根据店员id查询店员的微信id，再通过微信id查询当前店员是否在其他店铺启用
	if($state=="正常"){
		$sql = "select id from shop_staff where staff_state='正常' and staff_wxid = (select staff_wxid from shop_staff where id=$id)";
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){   //店员已在其他店铺启用
			$list = array("msg"=>"2");
			$mysqli->close();  //关闭数据库
			die(json_encode($list));
		}
	}
	//修改店员的状态
	$sql = "update shop_staff set staff_state='$state' where id=$id";
	$mysqli->query($sql);   //执行更新操作
	if($mysqli->affected_rows>0){  //更新操作影响数为0，说明更新成功
		$list = array("msg"=>"1");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	} else {                 //更新操作影响数不为0，说明更新成功
		$list = array("msg"=>"00");
		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	};
    $mysqli->close();  //关闭数据库
?>
