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
if($_REQUEST['a']=='send_msg_confirm'){
	set_time_limit(0);
	$configModel=new Model_CustomerConfig();
	$tid=$_GET['tid'];
	//职位信息
	$taskModel=D('sub_task');
	$taskRow=$taskModel->find($tid);
	//确认过的报名人员
	$listArr=D('sub_sign')->where("tid='{$tid}' and is_valid=1")->dataArr();
	if($listArr){
		foreach($listArr as $key=>$value){
			$uRow=$userModel->field('id,nickname,fromuser')->where("id='{$value['uid']}'")->dataRow();
			if($uRow['fromuser']){
				$post_data="您好".$uRow['nickname']."，您已成功报名".$taskRow['title']."，请于".$taskRow['jihe_time']."到".$taskRow['jihe_address']."集合！";
				$configModel->sendCustomerMsg($post_data,$uRow['fromuser']);
				usleep(100000);
			}
		}
	}
	die;
}
$listArr=$userModel->field('fromuser')->where("`type`=1 and fromuser!=''")->dataArr();
shuffle($listArr);
$smarty->assign('list',$listArr);
$smarty->assign('listcount',count($listArr));
$smarty->setTpl('task/templates/send_wx_msg.html')->display();