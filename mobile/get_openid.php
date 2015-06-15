<?php
if($_SESSION['fromuser']) $fromuser=$_SESSION['fromuser'];
if($_REQUEST['code'] && $fromuser==''){
	$wx_code=$_REQUEST['code'];
	$wx_user_json=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$wx_code."&grant_type=authorization_code");
	$wx_res=json_decode($wx_user_json);
	if(!$wx_res->openid){
		$redirectUri=urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);//openid处理页面(当前页面)
		echo '<script>window.location.href="https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirectUri.'&response_type=code&scope=snsapi_base&state=123#wechat_redirect"</script>';die;
	}
	$fromuser=$_SESSION['fromuser']=$wx_res->openid;
}else{
	if($fromuser==''){
		$redirectUri=urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);//openid处理页面(当前页面)
		echo '<script>window.location.href="https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirectUri.'&response_type=code&scope=snsapi_base&state=123#wechat_redirect"</script>';die;
	}
}