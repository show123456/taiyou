<?php
require_once("../includes/config.inc.php");

$mobile = $_POST['mobile'];
$send_code = $_POST['send_code'];

$mobile_code = code_random(4,1);
if(empty($mobile)){
	exit('手机号码不能为空');
}

//防用户恶意请求
if(empty($_SESSION['send_code']) or $send_code!=$_SESSION['send_code']){
	exit('请求超时，请刷新页面后重试');
}

$post_data="您的验证码是：".$mobile_code."。请不要把验证码泄露给其他人。如非本人操作，可不用理会！";
$gets=xml_to_array(send_phone_msg($mobile,$post_data));
//file_put_contents('../data/sms.txt',$post_data);$gets['SubmitResult']['code']=2;
/* if($gets['SubmitResult']['code']==2){
	$_SESSION['mobile'] = $mobile;
	$_SESSION['mobile_code'] = $mobile_code;//手机验证码
	echo 'suc';
}else{
	echo 'err';
} */
$_SESSION['mobile'] = $mobile;
$_SESSION['mobile_code'] = $mobile_code;//手机验证码
echo 'suc';die;
?>