<?php
header("Content-type: text/html; charset=utf-8");
header("pragma:no-cache");
include_once("inc/DB.php");
chkLogin("index.php");//微信登录授权
checkUsercode("shop"); //判断微信openId是否有门店操作权限
$id = $_GET['id'];
$shop_id = $_GET['shop_id'];
if($id=='' || $id==null || $shop_id=='' || $shop_id==null){
    $mysqli->close();
    die('<script>alert("参数错误！");history.go(-1);</script>');
}
?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes">
<title>店员详情-赛店宝</title>
<link rel="stylesheet" type="text/css" href="./css/css.css?t=16673">
<script language="javascript" src="js/jquery-1.10.1.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<style>
.button {
  display: inline-block;
  text-align: center;
  padding: 0.14286rem 0.42857rem;
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
  border-radius: 0.35714rem;
  margin: 0rem 0.14286rem; }
        label{text-align:right;display:inline-block;margin:5px 5px 5px 10px;}
        input,textarea{margin:5px 0px;padding:2px 5px;}
        img{width:60px;}
        .btn-big{margin:0 10px;padding:5px 10px;}
    </style>
<body>

  <div align="center" class="head_back" onclick="location.href='boss_index.php?id=<?php echo $shop_id;?>';">
   <h2 align="center">返回上级页面 </h2>
  </div>
<?php

    $sql = "select * from shop_staff where id=$id";
    $result = $mysqli->query($sql);
	
?>
<div class="clear-fix"></div>
        <?php
            if($result->num_rows>0){
                $row = $result->fetch_array();
				?>
                    <div>
                      <div align="center"><img src="<?php echo $row['staff_headimg'] ?>" /></div>
                    </div>
                    <div><label for="staff_nickname">微信昵称：</label><?php echo $row['staff_nickname'] ?></div>
                    <div><label for="login_count">登录次数：</label><?php echo $row['login_count'] ?>次</div>
                    <div><label for="order_count">录入数量：</label><?php echo $row['order_count'] ?>单</div>
                    <div><label for="add_time">添加时间：</label><?php echo $row['add_time'] ?></div>
                    <div><label for="last_login">最后登录：</label><?php echo $row['last_login'] ?></div>
                    <div><label for="staff_state">账号状态：</label><span style="color:<?php echo $row['staff_state']=="正常"?"green":"red"; ?>;"><?php echo $row['staff_state']; ?></span>
					<?php if ($row['staff_state']=="正常") {?>	<button class="button red btn-big" onclick="setState(0,'<?php echo $row['id'] ?>');">禁用</button>
					<?php  } else { ?><button class="button green btn-big" onclick="setState(1,'<?php echo $row['id'] ?>');">启用</button> <?php }?>
                    </div>
                    <div><label for="staff_name">真实姓名：</label><input type="text" id="staff_name" name="staff_name" value="<?php echo $row['staff_name'] ?>"/></div>
                    <div><label for="staff_tel">联系电话：</label><input type="text" id="staff_tel" name="staff_tel" value="<?php echo $row['staff_tel'] ?>"/></div>
                    <div class="clear-fix"><label for="staff_address" style="float:left;">联系地址：</label>
					<textarea  style="float:left;" rows="5" id="staff_address" name="staff_address"><?php echo $row['staff_address'] ?></textarea></div>
                   
					<div style="text-align:center;margin-top:10px;">
                        <button class="button orange btn-big" onclick="editDetail('<?php echo $row['id'] ?>');">修改</button>
                        <button class="button green btn-big" onclick="location.href='boss_index.php?id=<?php echo $shop_id;?>';">返回</button>
                    </div>
              <?php  
            } else {
                echo '<div class="no-data">没有数据</div>';
            }
        ?>
    </div>
    <script>
        function setState(state,id){   //修改业务员状态
            $.ajax({
                type : "POST",  //提交方式
                url : "staff_changeState.php",//路径
                dataType: "json",
                data : {
                    "id" : id,
                    "state" : state
                },//数据，这里使用的是Json格式进行传输
                success:function(json) {    //返回数据根据结果进行相应的处理
                    if(json.msg==1){   //返回成功
                        alert("操作成功");
                        location.reload();
                    }else if(json.msg==2){
                        alert("当前店员已在其他店铺启用");
                    }else {
                        alert("操作失败");
                    }
                },
                error:function(){
                    alert("error");
                }
            });
        }
        function editDetail(id){        //修改业务员资料
            var staff_name = $("#staff_name").val();
            var staff_tel = $("#staff_tel").val();
            var staff_address = $("#staff_address").val();
            if(!checkMobile(staff_tel)&&!checkPhone(staff_tel)){
                alert("您输入的电话号码有误！");
                $("#staff_tel").focus();
                return false;
            }
            $.ajax({
                type : "POST",  //提交方式
                url : "staff_editDetail.php",//路径
                dataType: "json",
                data : {
                    "id" : id,
                    "staff_name" : staff_name,
                    "staff_tel" : staff_tel,
                    "staff_address" : staff_address
                },//数据，这里使用的是Json格式进行传输
                success:function(json) {    //返回数据根据结果进行相应的处理
                    if(json.msg==1){   //返回成功
                        alert("修改业务员资料成功");
                        location.reload();
                    }else {
                        alert("修改业务员资料失败");
                    }
                },
                error:function(){
                    alert("error");
                }
            });
        }
        function checkMobile(str) {   //验证手机号码，验证规则：11位数字，以1开头。
            var re = /^1\d{10}$/
            if (re.test(str)) {
                return true;
            } else {
                return false;
            }
        }
        function checkPhone(str){   //验证座机，如01088888888,010-88888888,0955-7777777
            var re = /^0\d{2,3}-?\d{7,8}$/;
            if(re.test(str)){
                return true;
            }else{
                return false;
            }
        }
    </script>
  <div align="center">
  <br />当前时间: <?php echo date('Y-m-d h:i:s',time());
     $mysqli->close();

  ?>
  </div>
</body>
 </html>