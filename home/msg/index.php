<?php
include_once("../../includes/config.inc.php");
$configModel=new Model_CustomerConfig();
$userModel=new Model_Subtable('sub_user');
//微信通道
if(method_is('post')){
	$configModel->sendCustomerMsg($_POST['content'],$_POST['fromuser']);
}else{
	$listArr=$userModel->field('fromuser')->where("`type`!=2 and fromuser!='' group by fromuser")->dataArr();
	$smarty->assign('list',$listArr);
	$smarty->assign('listcount',count($listArr));
	$smarty->setTpl('msg/templates/add.html')->display();die();
}