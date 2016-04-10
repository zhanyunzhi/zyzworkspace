<?php
header("Content-type: text/html; charset=utf-8");
header("pragma:no-cache");
include_once("inc/DB.php");
chkLogin("shop_index.php");//微信登录授权
checkUsercode("shop_developer");

require_once "jssdk.php";
$jssdk = new JSSDK("wx01a89c3c909e5be8", "45bca554dc70fbe36cdbfd085289609f");
$signPackage = $jssdk->GetSignPackage();

?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes">
<title>门店详情-赛店宝</title>
<link rel="stylesheet" type="text/css" href="assets/css/css.css?t=16673">
<script language="javascript" src="assets/js/jquery-1.10.1.min.js"></script>
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

  <div align="center" class="head_back" onclick="location.href='shop_index.php';">
   <h2 align="center">返回上级页面 </h2>
  </div>
    <?php 

$id = $_GET['id'];
if($id=='' || $id==null){
   $mysqli->close();
   die('<script>alert("参数错误！");history.go(-1);</script>');
}
    $sql = "select * from shop where id=$id";
    $result = $mysqli->query($sql);

?>
<div class="clear-fix"></div>
        <?php
            if($result->num_rows>0){
$row = $result->fetch_array();
if($row['developer_wxid']!=$_COOKIE["openId"])  //ID记录的wxid与当前用户不一致
{
	$result->free();
	$mysqli->close();
	header("location:index.php");
	die();
}
				?>
                    <div>
                      <div align="center"><img src="<?php echo $row['boss_headimgurl'] ?>" /></div>
                    </div>
					<div><label for="developer_time">发展时间：</label><?php echo $row['developer_time'] ?></div>
                    <div><label for="staff_count">员工数量：</label><?php echo $row['staff_count'] ?></div>
                   
                    <div><label for="state">账号状态：</label><span style="color:<?php echo $row['state']=="正常"?"green":"red"; ?>;"><?php echo $row['state'] ?></span> 
					<?php if ($row['state']=="正常") {?>	<button class="button red btn-big" onclick="setState(0,'<?php echo $row['id'] ?>');">禁用</button>
					<?php  } else { ?><button class="button green btn-big" onclick="setState(1,'<?php echo $row['id'] ?>');">启用</button> <?php }?>
                    </div>
                     <div><label for="boss_name">老板姓名：</label><input type="text" id="boss_name" name="boss_name" value="<?php echo $row['boss_name'] ?>"/></div>
                    <div><label for="shop_name">门店名称：</label><input type="text" id="shop_name" name="shop_name" value="<?php echo $row['shop_name'] ?>"/></div>
<div><label for="boss_tel">联系电话：</label><input name="boss_tel" type="text" id="boss_tel" value="<?php echo $row['boss_tel'] ?>" maxlength="11"/>
                    </div>
                    <div class="clear-fix"><label for="shop_address" style="float:left;">门店地址：</label>
					<textarea  style="float:left;" rows="5" id="shop_address" name="shop_address"><?php echo $row['shop_address'] ?></textarea></div>
                    <div class="clear-fix">
                        <label for="shop_img" style="float:left;">门店照片：</label><img src="<?php echo $row['shop_img'] ?>" id="shop_img" style="margin:5px 0;"/>
                        <input type="hidden" id="shop_img_value" value="<?php echo $row['shop_img'] ?>" />
                    </div>
                    <div class="clear-fix">
                        <label for="shop_doc_img" style="float:left;">证件照片：</label><img src="<?php echo $row['shop_doc_img'] ?>" id="shop_doc_img" style="margin:5px 0;"/>
                        <input type="hidden" id="shop_doc_img_value" value="<?php echo $row['shop_doc_img'] ?>" />
                    </div>
                    <div class="clear-fix">
                        <label for="shop_other_img" style="float:left;">其他照片：</label><img src="<?php echo $row['shop_other_img'] ?>" id="shop_other_img" style="margin:5px 0;"/>
                        <input type="hidden" id="shop_other_img_value" value="<?php echo $row['shop_other_img'] ?>" />
                    </div>
					<div style="text-align:center;margin-top:10px;">
                        <button class="button orange btn-big" onclick="editDetail('<?php echo $row['id'] ?>');">修改</button>
                        <button class="button green btn-big" onclick="location.href='shop_index.php';">返回</button>
                    </div>
              <?php  
			  $result->free();
            } else {
                echo '<div class="no-data">没有数据</div>';
            }
        ?>
    </div>
    <script>
        function setState(state,id){   //修改业务员状态
            $.ajax({
                type : "POST",  //提交方式
                url : "shop_changeState.php",//路径
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
            var shop_name = $("#shop_name").val();
			var boss_name = $("#boss_name").val();
            var boss_tel = $("#boss_tel").val();
            var shop_address = $("#shop_address").val();
            var shop_img = $("#shop_img_value").val();
            var shop_doc_img = $("#shop_doc_img_value").val();
            var shop_other_img = $("#shop_other_img_value").val();
            //alert(shop_img+"=="+shop_doc_img+"=="+shop_other_img)
            if(!checkMobile(boss_tel)&&!checkPhone(boss_tel)){
                alert("您输入的电话号码有误！");
                $("#boss_tel").focus();
                return false;
            }
            $.ajax({
                type : "POST",  //提交方式
                url : "shop_editDetail.php",//路径
                dataType: "json",
                data : {
                    "id" : id,
                    "shop_name" : shop_name,
					"boss_name" : boss_name,
                    "boss_tel" : boss_tel,
                    "shop_address" : shop_address,
                    "shop_img" : shop_img,
                    "shop_doc_img" : shop_doc_img,
                    "shop_other_img" : shop_other_img
                },//数据，这里使用的是Json格式进行传输
                success:function(json) {    //返回数据根据结果进行相应的处理

					if(json.msg==1){   //返回成功
                        alert("修改门店资料成功");
                        location.reload();
                    }else {
                        alert("修改门店资料失败");
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
  <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
  <script>
      wx.config({
          debug: false,
          appId: '<?php echo $signPackage["appId"];?>',
          timestamp: <?php echo $signPackage["timestamp"];?>,
          nonceStr: '<?php echo $signPackage["nonceStr"];?>',
          signature: '<?php echo $signPackage["signature"];?>',
          jsApiList: [
              'chooseImage',
              'previewImage',
              'uploadImage',
              'downloadImage'
          ]
      });
      wx.ready(function () {
          // 5 图片接口
          // 5.1 拍照、本地选图
          var images = {
              localId: [],
              serverId: []
          };
          document.querySelector('#shop_img').onclick = function () {  //门店照片
              wx.chooseImage({
                  count: 1, // 默认9
                  success: function (res) {
                      images.localId = res.localIds;
                      //images.localId.push(res.localIds);
                      document.querySelector('#shop_img').src = res.localIds;
                      wx.uploadImage({  //上传图片到微信，以便后台下载
                          localId: images.localId[0],
                          success: function (res) {
                              images.serverId.push(res.serverId);
                              document.querySelector('#shop_img_value').value = res.serverId;  //将serverId存入一个隐藏表单中
                          },
                          fail: function (res) {
                              alert(JSON.stringify(res));
                          }
                      });
                  }
              });
          };
          document.querySelector('#shop_doc_img').onclick = function () {   //证件照片
              wx.chooseImage({
                  count: 1, // 默认9
                  success: function (res) {
                      images.localId = res.localIds;
                      //images.localId.push(res.localIds);
                      document.querySelector('#shop_doc_img').src = res.localIds;
                      wx.uploadImage({    //上传图片到微信，以便后台下载
                          localId: images.localId[0],
                          success: function (res) {
                              images.serverId.push(res.serverId);
                              document.querySelector('#shop_doc_img_value').value = res.serverId;  //将serverId存入一个隐藏表单中
                          },
                          fail: function (res) {
                              alert(JSON.stringify(res));
                          }
                      });
                  }
              });
          };
          document.querySelector('#shop_other_img').onclick = function () {   //其他照片
              wx.chooseImage({
                  count: 1, // 默认9
                  success: function (res) {
                      images.localId = res.localIds;
                      //images.localId.push(res.localIds);
                      document.querySelector('#shop_other_img').src = ""+res.localIds;
                      wx.uploadImage({    //上传图片到微信，以便后台下载
                          localId: images.localId[0],
                          success: function (res) {
                              images.serverId.push(res.serverId);
                              document.querySelector('#shop_other_img_value').value = res.serverId;  //将serverId存入一个隐藏表单中
                          },
                          fail: function (res) {
                              alert(JSON.stringify(res));
                          }
                      });
                  }
              });
          };
      });
      wx.error(function (res) {
          alert(res.errMsg);
      });
  </script>
</body>
 </html>