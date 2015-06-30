<?php
include_once("../includes/config.inc.php");
//if(!strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')) die("请在微信浏览器中打开");//判断是否微信打开
//cookie获取用户信息
if($_COOKIE['tyuid'] && !$_SESSION['tyuser']['id']) $_SESSION['tyuser']['id']=$_COOKIE['tyuid'];

$userModel=new Model_Subtable('sub_user');
if($_GET['m']=='task' && ($_GET['a']=='index' || $_GET['a']=='detail')){
	if($_SESSION['tyuser']['id']){
		$userRow=$userModel->find($_SESSION['tyuser']['id']);
		$smarty->assign('userRow',$userRow);
		if(!$userRow['pid']) $smarty->assign('nowanshan',1);//未完善资料
	}else{
		$smarty->assign('nosession',1);//未登录
	}
}else if($_GET['m']=='pic'){
	require_once "get_member_info.php";
}else{
	//判断登录
	if(empty($_SESSION['tyuser'])){
		header("Location:./login.php");die;
	}
	//当前登录用户信息
	$userRow=$userModel->find($_SESSION['tyuser']['id']);
	$smarty->assign('userRow',$userRow);
	if(!$userRow['pid']) $smarty->assign('nowanshan',1);//未完善资料
}
//判断是否禁登录
$banModel=new Model_Ban('ban');
$ban_res_login=$banModel->no_login_rights($userRow);
if($ban_res_login){
	setCookie('tyuid','',time()-1);
	$smarty->assign('info','您的账号还有'.ceil($ban_res_login/3600).'小时解禁');
	$smarty->setLayout('')->setTpl('mobile/templates/no_data.html')->display();die;
}
//路由
$_GET['m'] ? $controllerName=$_GET['m'] : $controllerName='index';
if(!$_GET['a']) $_GET['a']='index';
require_once "./controls/".$controllerName.".php";