<?php
    header("Content-type: text/html; charset=utf-8");
	header("pragma:no-cache");
	include_once("./inc/DB.php");	
	chkLogin("shop_index.php");//微信登录授权
	checkUsercode("shop_developer");

	function getAccessToken($appId,$appSecret) {  //获取access token
		// access_token 应该全局存储与更新，以下代码以写入到文件中做示例
		$data = json_decode(get_php_file("access_token.php"));
		if ($data->expire_time < time()) {
			// 如果是企业号用以下URL获取access_token
			// $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
			$res = json_decode(httpGet($url));
			$access_token = $res->access_token;
			if ($access_token) {
				$data->expire_time = time() + 7000;
				$data->access_token = $access_token;
				set_php_file("access_token.php", json_encode($data));
			}
		} else {
			$access_token = $data->access_token;
		}
		return $access_token;
	}
	function downloadWeixinFile($access_token,$mediaid){
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0);  //只取body
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$package = curl_exec($ch);
		$httpinfo = curl_getinfo($ch);
		curl_close($ch);
		$fileInfo = array_merge(array('header' => $httpinfo), array('body' => $package));
		$filename = 'upload/'.$mediaid.'.jpg';
		if(!saveWeixinFlie($filename,$fileInfo["body"])){
			$filename = '';  //保存图片失败
		}
		return $filename;
	}
	function saveWeixinFlie($filename,$fileContent){
		if (!file_exists('upload')){ mkdir ("upload");}//文件夹不存在则新建文件夹
		$local_file = fopen($filename,'w');
		if(false !== $local_file){
			if(false !== fwrite($local_file,$fileContent,1*1024*1024)){
				fclose($local_file);
				return true;  //保存成功
			}
		}
		return false;	//保存失败
	}
	function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		// 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
		// 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
		curl_setopt($curl,CURLOPT_CAINFO,dirname(__FILE__).'/cacert.pem');
		curl_setopt($curl, CURLOPT_URL, $url);

		$res = curl_exec($curl);
		curl_close($curl);

		return $res;
	}
	function get_php_file($filename) {
		return trim(substr(file_get_contents($filename), 15));
	}
	function set_php_file($filename, $content) {
		$fp = fopen($filename, "w");
		fwrite($fp, "<?php exit();?>" . $content);
		fclose($fp);
	}

    //获取参数
	$id = $_POST['id'];  //获取业务员id
	$shop_name = $_POST['shop_name'];   //获取门店名称
	$boss_name = $_POST['boss_name'];   //获取门店老板联系电话
	$boss_tel = $_POST['boss_tel'];   //获取门店联系电话
	$shop_address = $_POST['shop_address'];   //获取门店地址
	$shop_img = $_POST['shop_img'];   //获取门店照片的微信serverId
	$shop_doc_img = $_POST['shop_doc_img'];   //获取证件照片的微信serverId
	$shop_other_img = $_POST['shop_other_img'];   //获取其他照片的微信serverId
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
	if(inject_check($shop_name))
	{
		$list = array("msg"=>"0");
 		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	}
	if(inject_check($boss_tel))
	{
		$list = array("msg"=>"0");
 		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	}
	if(inject_check($shop_address))
	{
		$list = array("msg"=>"0");
 		$mysqli->close();  //关闭数据库
		die(json_encode($list));
	}
	$access_token = '';
	if(($shop_img!=''&& !strstr($shop_img,"upload"))||($shop_doc_img!='' && !strstr($shop_doc_img,"upload"))||($shop_other_img!='' && !strstr($shop_other_img,"upload"))){
		$access_token = getAccessToken("wx01a89c3c909e5be8", "45bca554dc70fbe36cdbfd085289609f");
	}
	if($shop_img!='' && !strstr($shop_img,"upload")){  //从微信服务器上下载门店照片
		$shop_img = downloadWeixinFile($access_token,$shop_img);
	}
	if($shop_doc_img!='' && !strstr($shop_doc_img,"upload")){	//从微信服务器上下载证件照片
		$shop_doc_img = downloadWeixinFile($access_token,$shop_doc_img);
	}
	if($shop_other_img!='' && !strstr($shop_other_img,"upload")){	//从微信服务器上下载其他照片
		$shop_other_img = downloadWeixinFile($access_token,$shop_other_img);
	}
//	$list = array("msg"=>$shop_img);
//	$mysqli->close();  //关闭数据库
//	die(json_encode($list));
	//die($access_token);

	//修改业务员的信息
	$sql = "update shop set shop_name='$shop_name',boss_name='$boss_name',boss_tel='$boss_tel',shop_address='$shop_address',shop_img='$shop_img',shop_doc_img='$shop_doc_img',shop_other_img='$shop_other_img',edit_time=now() where id=$id";
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
