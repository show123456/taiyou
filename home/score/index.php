<?php
include_once("../../includes/config.inc.php");
check_login();//验证是否登录
$model=D('sub_score');
//数据修改
if($_REQUEST['a']=='tjr'){
	if(method_is('post')){
		$data=$_POST;
		$res=$model->add($data);
		echo json_encode($res);die();
	}else{
		$vo=$model->where("name='tjr'")->dataRow();
		$smarty->assign('vo',$vo);
		$smarty->setTpl('score/templates/tjr_add.html')->display();die();
	}
}
if($_REQUEST['a']=='qd'){
	if(method_is('post')){
		$data=$_POST;
		$res=$model->add($data);
		echo json_encode($res);die();
	}else{
		$vo=$model->where("name='qd'")->dataRow();
		$smarty->assign('vo',$vo);
		$smarty->setTpl('score/templates/qd_add.html')->display();die();
	}
}