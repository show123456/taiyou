<?php
include_once("../../includes/config.inc.php");
$configModel=new Model_CustomerConfig();
$userModel=new Model_Subtable('sub_user');
//微信通道
if(method_is('post')){
	$jzUserArr=$userModel->where("fromuser!='' and type=1")->dataArr();
	if($jzUserArr){
		$jzUserRow=array();
		foreach($jzUserArr as $k=>$v){
			$jzUserRow[]=$v['fromuser'];
		}
		$configModel->sendCustomerMsg($_POST['content'],$jzUserRow);
	}
	echo json_encode('suc');die();
}else{
	$listArr=$userModel->field('fromuser')->where("`type`!=2 and fromuser!=''")->dataArr();
	shuffle($listArr);
	$smarty->assign('list',$listArr);
	$smarty->assign('listcount',count($listArr));
	$smarty->setTpl('msg/templates/add.html')->display();die();
}