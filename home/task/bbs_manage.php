<?php
include_once("../../includes/config.inc.php");
check_login();//验证是否登录
$postTable=new Model_Subtable('sub_task');
$replyTable=new Model_Subtable('sub_reply');
//回复数据的添加
if($_POST['action']=='add'){
	//管理员头像
	$uRow=D('sub_user')->find(1);
	$data['info']['head_pic']='/data/image_c/'.$uRow['head_pic'];
	
	$data['info']['tid']=(int)$_POST['tid'];
	$data['info']['content']=str_inmysql($_POST['content']);
	$data['info']['name']='云姐';
	$res=$replyTable->add($data);
	if($res) echo json_encode($res);exit;
}
//数据删除
if($_GET['action']=='del'){
	$id=(int)$_GET['id'];
	$res=$replyTable->del($id);
	echo json_encode($res);exit;
}
//根据ID查询帖子信息
$id=(int)$_GET['id'];
$vo=$postTable->find($_GET['id']);
//回复列表
$filter['where']=" tid=".$id;
$filter['order'] = " id desc ";
$data = $replyTable->paginate($filter,'*',$_GET['p'],5);
//分页信息
$smarty->assign('page',$replyTable->pager($data['pager']));
$smarty->assign('vo',$vo);
$smarty->assign('replyArr',$data['data']);
$smarty->setTpl('task/templates/bbs_manage.html')->display();
?>