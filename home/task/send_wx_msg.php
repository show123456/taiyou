<?php
include_once("../../includes/config.inc.php");
$model=new Model_Subtable('sub_task');
$userModel=new Model_Subtable('sub_user');
if($_REQUEST['a']=='send'){
	if(!$_GET['tid'] || !$_GET['fromuser']) die;
	$tid=$_GET['tid'];
	$taskRow=$model->find($tid);
	//发布方公司名
	if($taskRow['company_name']){
		$cname=$taskRow['company_name'];
	}else if($taskRow['uid']==1){
		$cname='云客驿站';
	}else{
		$userRow=$userModel->find($taskRow['uid']);
		$cname=$userRow['nickname'];
	}
	$configModel=new Model_CustomerConfig();
	$configModel->sendCustomerMsg($cname.'发布了一个新职位：'.$taskRow['title']."，<a href='http://www.yunfanke.com/mobile/index.php?m=task&a=detail&id=".$tid."'>点击查看</a>",$_GET['fromuser']);
	die;
}
$listArr=$userModel->field('fromuser')->where("`type`=1 and fromuser!=''")->dataArr();
shuffle($listArr);
$smarty->assign('list',$listArr);
$smarty->assign('listcount',count($listArr));
$smarty->setTpl('task/templates/send_wx_msg.html')->display();