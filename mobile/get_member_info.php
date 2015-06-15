<?php
if($_SESSION['tyuser']['id']){
	$userModel=new Model_Subtable('sub_user');
	$userRow=$userModel->find($_SESSION['tyuser']['id']);
	$smarty->assign('userRow',$userRow);
}
if(empty($_SESSION['picuser'])){
	if($_SESSION['tyuser']){
		$memberModel=new Model_Subtable('member');
		$picUserRow=$memberModel->where("fromuser='".$_SESSION['tyuser']['fromuser']."'")->dataRow();
		if($picUserRow){
			$_SESSION['picuser']=$picUserRow;
		}else{
			echo '<script>window.location.href="https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']).'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect"</script>';die;
		}
	}else{
		if($_REQUEST['code']){
			//第二步：换取网页授权access_token
			$wx_code=$_REQUEST['code'];
			$wx_user_json=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$wx_code."&grant_type=authorization_code");
			$wx_res=json_decode($wx_user_json);
			//第四步：拉取用户信息
			$wx_user_info_json=file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=".$wx_res->access_token."&openid=".$wx_res->openid."&lang=zh_CN");
			$wx_user_info=json_decode($wx_user_info_json,true);
			
			$wx_user_info['fromuser']=$wx_user_info['openid'];
			$_SESSION['picuser']=$wx_user_info;
		}else{
			$redirectUri=urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);//openid处理页面(当前页面)
			echo '<script>window.location.href="https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirectUri.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect"</script>';die;
		}
	}
}