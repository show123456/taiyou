<?php
include_once("../includes/config.inc.php");
//if(!strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')) die("请在微信浏览器中打开");//判断是否微信打开
$model=new Model_Subtable('sub_user');
//注册
if($_REQUEST['a']=='do'){
	$data=$_POST;
	//手机验证码核对
	if($data['code']!=$_SESSION['mobile_code'] or empty($data['code'])){
		echo 'codeerr';die;
	}
	$row=$model->where("username='".$data['username']."'")->dataRow();
	//数据插入
	$info['num']['id']=$row['id'];
	$info['info']['pass']=md5($data['pass']);
	$res=$model->add($info);
	if($res){
		$_SESSION['mobile']='';
		$_SESSION['mobile_code']='';
	}
	echo $res;die;
}

//防止恶意请求的随机码
$_SESSION['send_code'] = code_random(6,1);
$smarty->assign('send_code',$_SESSION['send_code']);
$smarty->setLayout('')->setTpl('mobile/templates/editp.html')->display();