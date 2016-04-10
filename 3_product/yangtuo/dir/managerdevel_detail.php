<?php
header("Content-type: text/html; charset=utf-8");
header("pragma:no-cache");
include_once("inc/DB.php");
chkLogin("managerdevel_detail.php");//微信登录授权
checkUsercode("admin"); //判断微信openId是否有管理员操作权限
?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes">
<title>平台业务经理详情-赛店宝</title>
<link rel="stylesheet" type="text/css" href="assets/css/css.css?t=16673">
<script language="javascript" src="assets/js/jquery-1.10.1.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
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

  <div align="center" class="head_back" onclick="location.href='managerdevel_index.php';">
   <h2 align="center">返回上级页面 </h2>
  </div>
    <?php 

$id = $_GET['id'];
    if($id=='' || $id==null){
        $mysqli->close();
        die('<script>alert("参数错误！");history.go(-1);</script>');
    }
    $sql = "select * from managerdevel where id=$id";
    $result = $mysqli->query($sql);
	
?>
<div class="clear-fix"></div>
        <?php
            if($result->num_rows>0){
                $row = $result->fetch_array();
				?>
                    <div>
                      <div align="center"><img src="<?php echo $row['headimgurl'] ?>" /></div>
                    </div>
                    <div><label for="nickname">微信昵称：</label><?php echo $row['nickname'] ?></div>
                    <div><label for="shop_count">发展业务员：</label><?php echo $row['devel_count'] ?>人</div>
                    <div><label for="add_time">添加时间：</label><?php echo $row['add_time'] ?></div>
                    <div><label for="add_time">账号状态：</label><span style="color:<?php echo $row['state']=="正常"?"green":"red"; ?>;"><?php echo $row['state'] ?></span> 
					<?php if ($row['state']=="正常") {?>	<button class="button red btn-big" onclick="setState(0,'<?php echo $row['id'] ?>');">禁用</button>
					<?php  } else { ?><button class="button green btn-big" onclick="setState(1,'<?php echo $row['id'] ?>');">启用</button> <?php }?>
                    </div>
                    <div><label for="developer">真实姓名：</label><input type="text" id="name" name="name" value="<?php echo $row['name'] ?>"/></div>
                    <div><label for="tel">联系电话：</label><input type="text" id="tel" name="tel" value="<?php echo $row['tel'] ?>"/></div>
                    <div class="clear-fix"><label for="remark" style="float:left;">备注信息：</label>
					<textarea  style="float:left;" rows="5" id="remark" name="remark"><?php echo $row['remark'] ?></textarea></div>
                   
					<div style="text-align:center;margin-top:10px;">
                    <button class="button orange btn-big" onclick="editDetail('<?php echo $row['id'] ?>');">修改</button>
                    <button class="button green btn-big" onclick="location.href='managerdevel_index.php';">返回</button>
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
                url : "managerdevel_changeState.php",//路径
                dataType: "json",
                data : {
                    "id" : id,
                    "state" : state
                },//数据，这里使用的是Json格式进行传输
                success:function(json) {    //返回数据根据结果进行相应的处理
                    if(json.msg==1){   //返回成功
                        alert("操作成功");
                        location.reload();
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
            var name = $("#name").val();
            var tel = $("#tel").val();
            var remark = $("#remark").val();
            if(!checkMobile(tel)&&!checkPhone(tel)){
                alert("您输入的电话号码有误！");
                $("#tel").focus();
                return false;
            }
            $.ajax({
                type : "POST",  //提交方式
                url : "managerdevel_editDetail.php",//路径
                dataType: "json",
                data : {
                    "id" : id,
                    "name" : name,
                    "tel" : tel,
                    "remark" : remark
                },//数据，这里使用的是Json格式进行传输
                success:function(json) {    //返回数据根据结果进行相应的处理
                    if(json.msg==1){   //返回成功
                        alert("修改店铺业务员资料成功");
                        location.reload();
                    }else {
                        alert("修改店铺业务员资料失败");
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