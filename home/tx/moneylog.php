<?php
include_once("../../includes/config.inc.php");
check_login();//验证是否登录
$model=new Model_Subtable('sub_money_log');
$userModel=new Model_Subtable('sub_user');
$taskModel=new Model_Subtable('sub_task');
$typeArr=array(0=>'转账日结',1=>'充值',2=>'提现',3=>'转账日结',4=>'支付申请费',5=>'退还申请费',6=>'系统充值');

$filter['order'] = " id desc ";
$data = $model->paginate($filter,'*',common_pg('p'),10);
$listArr = $data['data'];
foreach($listArr as $key=>$value){
	$userRow=$userModel->find($value['uid']);
	$listArr[$key]['nickname']=$userRow['nickname'];
	$listArr[$key]['username']=$userRow['username'];
	$listArr[$key]['type']=$typeArr[$value['type']];
}
$smarty->assign('list',$listArr);
$smarty->assign('page',$model->pager($data['pager']));
$smarty->setTpl('tx/templates/moneylog.html')->display();