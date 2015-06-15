<?php
include_once("../includes/config.inc.php");
//if(!strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')) die("请在微信浏览器中打开");//判断是否微信打开
$model=new Model_Subtable('sub_user');
//登录
if($_REQUEST['a']=='dologin'){
	$data=$_POST;
	$userRow=$model->field('id,username,pass,fromuser')->where("username='".$data['username']."'")->dataRow();
	if(!$userRow){
		echo 'usernameerr';die;
	}else if($userRow['pass']!=md5($data['pass'])){
		echo 'passerr';die;
	}else{
		//写cookie
		setCookie('tyuid',$userRow['id'],time()+3600*24*720);
		
		$_SESSION['tyuser']=$userRow;
		echo 'success';die;
	}
}
$smarty->setLayout('')->setTpl('mobile/templates/login.html')->display();