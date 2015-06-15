<?php
$model=new Model_Subtable('sub_money_log');
//充值
if($_REQUEST['a']=='cz'){
	$czModel=new Model_Subtable('sub_cz');
	$data=array();
	$data['info']['uid']=$userRow['id'];
	$data['info']['money']=(int)$_POST['money'];
	if($data['info']['uid'] < 1 || $data['info']['money']<1){
		die('err');
	}
	$res=$czModel->add($data);
	if($res){
		echo 'cz_'.$res;//充值订单号
		die;
	}else{
		die('err');
	}
}