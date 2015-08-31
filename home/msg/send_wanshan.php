<?php
include_once("../../includes/config.inc.php");
$configModel=new Model_CustomerConfig();
$userModel=new Model_Subtable('sub_user');
//微信通道
if(method_is('post')){
	$content="亲~，你已成功注册云客驿站平台，但个人资料至今还未完善哦，请尽快完善。为了能及时参加兼职工作，请至“个人中心”－“我的金库”绑定中国银行卡，（除本地中国银行卡外，其他银行会产生2.5元手续费；异地银行卡产生5.5元手续费哦），如有疑问，请在左下方客服窗口咨询云姐哦~";
	$configModel->sendCustomerMsg($content,$_POST['fromuser']);
}else{
	$listArr=$userModel->field('fromuser')->where("is_see=0 and fromuser!='' group by fromuser")->dataArr();
	$smarty->assign('list',$listArr);
	$smarty->assign('listcount',count($listArr));
	$smarty->setTpl('msg/templates/send_wanshan.html')->display();die();
}