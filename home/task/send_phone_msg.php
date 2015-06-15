<?php
include_once("../../includes/config.inc.php");
$model=new Model_Subtable('sub_sign');
$userModel=new Model_Subtable('sub_user');
if($_REQUEST['a']=='send'){
	$name=$_GET['name'];
	$phone=$_GET['phone'];
	$tid=$_GET['tid'];
	//职位信息
	$taskModel=D('sub_task');
	$taskRow=$taskModel->find($tid);
	
	$post_data="你好".$name."（手机尾号为".substr($phone,-4)."），你已成功报名".$taskRow['title']."，请于".$taskRow['jihe_time']."到".$taskRow['jihe_address']."集合！";
	send_phone_msg($phone,$post_data);
	die;
}
$listArr=$model->where("tid='{$_GET['tid']}' and is_valid=1")->dataArr();
foreach($listArr as $key=>$value){
	$uRow=$userModel->field('id,username,nickname')->where("id='{$value['uid']}'")->dataRow();
	$listArr[$key]=$uRow;
	$listArr[$key]['xuhao']=$key+1;
}
$smarty->assign('list',$listArr);
$smarty->assign('tid',$_GET['tid']);
$smarty->setTpl('task/templates/send_phone_msg.html')->display();