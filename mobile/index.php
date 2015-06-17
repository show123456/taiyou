<?php
include_once("../includes/config.inc.php");
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
//路由
$_GET['m'] ? $controllerName=$_GET['m'] : $controllerName='index';
if(!$_GET['a']) $_GET['a']='index';
require_once "./controls/".$controllerName.".php";