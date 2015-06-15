<?php
include_once("../includes/config.inc.php");
//判断登录
if(empty($_SESSION['tyuser'])){
	header("Location:./login.php");die;
}
//当前登录用户信息
$userModel=new Model_Subtable('sub_user');
$userRow=$userModel->find($_SESSION['tyuser']['id']);

if($userRow['type']==2){
	header("Location:index.php?m=user&a=user_index");die;
}else{
	header("Location:index.php?m=task&a=index");die;
}